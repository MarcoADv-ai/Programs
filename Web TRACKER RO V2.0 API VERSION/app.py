from flask import Flask, Response, jsonify, request, send_from_directory, session
from datetime import datetime, timedelta
from pathlib import Path
from html import unescape
from functools import wraps
import hashlib
import hmac
import json
import os
import sqlite3
import threading
import time
import uuid
import secrets
import pytz
import urllib3

from database import TrackerDatabase


BASE_DIR = Path(__file__).resolve().parent
DATABASE_FILE = BASE_DIR / "tracker.db"
BRIGHTDATA_API_TOKEN = os.environ.get("BRIGHTDATA_API_TOKEN", "").strip()
BRIGHTDATA_COLLECTOR_ID = os.environ.get(
    "BRIGHTDATA_COLLECTOR_ID", "c_msqxx5lz2bpzale8wo"
).strip()
SAKURA_RANKING_URL = "https://sakura-ro.com/?module=ranking&action=mvp"
# El ranking de Sakura RO publica sus timestamps en UTC-5.
SAKURA_TZ = pytz.timezone("America/Bogota")
POLL_SECONDS = 600
BRIGHTDATA_RESULT_POLL_SECONDS = 5
BRIGHTDATA_RESULT_TIMEOUT_SECONDS = 300
BRIGHTDATA_HTTP = urllib3.PoolManager(
    timeout=urllib3.Timeout(connect=10, read=30),
    retries=urllib3.Retry(
        total=3,
        connect=3,
        read=3,
        status=3,
        status_forcelist={429, 500, 502, 503, 504},
        backoff_factor=1,
        allowed_methods=frozenset({"GET", "POST"}),
    ),
)

app = Flask(__name__, static_folder="dist/assets", static_url_path="/assets")
app.secret_key = os.environ.get("FLASK_SECRET_KEY") or secrets.token_hex(32)
app.config.update(
    SESSION_COOKIE_HTTPONLY=True,
    SESSION_COOKIE_SAMESITE="Lax",
    PERMANENT_SESSION_LIFETIME=timedelta(hours=12),
)


class OptionalTesteoPrefix:
    """Allow the same build to run behind Nginx or directly at /testeo1/."""

    def __init__(self, application, prefix="/testeo1"):
        self.application = application
        self.prefix = prefix

    def __call__(self, environ, start_response):
        path = environ.get("PATH_INFO", "")
        if path == self.prefix or path.startswith(f"{self.prefix}/"):
            environ = environ.copy()
            environ["SCRIPT_NAME"] = f"{environ.get('SCRIPT_NAME', '')}{self.prefix}"
            environ["PATH_INFO"] = path[len(self.prefix):] or "/"
        return self.application(environ, start_response)


app.wsgi_app = OptionalTesteoPrefix(app.wsgi_app)
data_lock = threading.RLock()
monitor_started = False
database = TrackerDatabase(DATABASE_FILE)
MAX_GIF_BYTES = 5 * 1024 * 1024
EXACT_DELAY_MVPS = {"Wounded Morroc", "Valkyrie Randgris"}
ADMIN_USERNAME = os.environ.get("ADMIN_USERNAME", "admin").strip()
ADMIN_PASSWORD = os.environ.get("ADMIN_PASSWORD", "")
ADMIN_PASSWORD_SALT = os.environ.get("ADMIN_PASSWORD_SALT", "").encode("utf-8")
ADMIN_PASSWORD_HASH = os.environ.get("ADMIN_PASSWORD_HASH", "").strip()


def valid_admin_password(password):
    if ADMIN_PASSWORD:
        return hmac.compare_digest(str(password), ADMIN_PASSWORD)
    if not ADMIN_PASSWORD_SALT or not ADMIN_PASSWORD_HASH:
        return False
    candidate = hashlib.pbkdf2_hmac(
        "sha256", str(password).encode("utf-8"), ADMIN_PASSWORD_SALT, 600000
    ).hex()
    return hmac.compare_digest(candidate, ADMIN_PASSWORD_HASH)


def admin_required(function):
    @wraps(function)
    def protected(*args, **kwargs):
        if not session.get("is_admin"):
            return jsonify({"error": "Inicia sesión como administrador"}), 401
        token = request.headers.get("X-CSRF-Token", "")
        if not token or not hmac.compare_digest(token, session.get("csrf_token", "")):
            return jsonify({"error": "La sesión de seguridad no es válida"}), 403
        return function(*args, **kwargs)
    return protected


MVP_NAME_ALIASES = {
    "Memory of Thanatos": "Thanatos",
    "Nidhoggr&#039;s Shadow": "Nidhoggr's Shadow",
    "Nidhoggr’s Shadow": "Nidhoggr's Shadow",
}

MAP_NAME_ALIASES = {
    "tha_t12": "thana_t12",
}


def canonical_mvp_name(value):
    """Normalize ranking labels to the names used by the timer configuration."""
    name = " ".join(unescape(str(value)).replace("’", "'").split())
    return MVP_NAME_ALIASES.get(name, name)


def canonical_map_name(value):
    """Normalize ranking map labels to the names used by the timer configuration."""
    name = " ".join(unescape(str(value)).split())
    return MAP_NAME_ALIASES.get(name, name)


def canonical_entry(entry):
    """Return an API-safe copy with canonical MVP and map labels."""
    normalized = dict(entry)
    normalized["mvp"] = canonical_mvp_name(normalized.get("mvp", ""))
    normalized["map"] = canonical_map_name(normalized.get("map", ""))
    return normalized


def catalog_payload():
    raw = request.form.get("data")
    if raw:
        try:
            return json.loads(raw)
        except json.JSONDecodeError as error:
            raise ValueError("Los datos del formulario no son válidos") from error
    return request.get_json(silent=True) or {}


def validate_catalog_payload(payload):
    name = " ".join(str(payload.get("name", "")).split())
    if not name:
        raise ValueError("El nombre del MVP es obligatorio")
    try:
        minimum = int(payload.get("respawn_min_minutes"))
        maximum = int(payload.get("respawn_max_minutes"))
    except (TypeError, ValueError) as error:
        raise ValueError("Los tiempos de respawn deben ser números enteros") from error
    if minimum < 0 or maximum < minimum:
        raise ValueError("El respawn máximo debe ser igual o mayor al mínimo")
    maps = payload.get("maps")
    if not isinstance(maps, list) or not maps:
        raise ValueError("Agrega al menos un mapa")
    clean_maps = []
    seen = set()
    for item in maps:
        map_name = " ".join(str(item.get("map_name", "")).split())
        if not map_name:
            raise ValueError("Todos los mapas necesitan un nombre")
        if map_name.casefold() in seen:
            raise ValueError(f"El mapa {map_name} está repetido")
        seen.add(map_name.casefold())
        try:
            map_minimum = int(item.get("respawn_min_minutes", minimum))
            map_maximum = int(item.get("respawn_max_minutes", maximum))
        except (TypeError, ValueError) as error:
            raise ValueError(f"El respawn de {map_name} no es válido") from error
        if map_minimum < 0 or map_maximum < map_minimum:
            raise ValueError(f"El respawn de {map_name} tiene un rango inválido")
        clean_maps.append({
            "id": item.get("id"),
            "map_name": map_name,
            "respawn_min_minutes": map_minimum,
            "respawn_max_minutes": map_maximum,
            "is_override": map_minimum != minimum or map_maximum != maximum,
        })
    return {
        "name": name,
        "respawn_min_minutes": minimum,
        "respawn_max_minutes": maximum,
        "shared_spawn_group": " ".join(str(payload.get("shared_spawn_group", "")).split()) or None,
        "maps": clean_maps,
    }


def save_uploaded_gif(upload):
    if not upload or not upload.filename:
        return None
    content = upload.read(MAX_GIF_BYTES + 1)
    if len(content) > MAX_GIF_BYTES:
        raise ValueError("El GIF no puede pesar más de 5 MB")
    if not content.startswith((b"GIF87a", b"GIF89a")):
        raise ValueError("El archivo debe ser un GIF válido")
    filename = f"custom-{uuid.uuid4().hex}.gif"
    return filename, content


def delete_custom_sprite(filename):
    if not filename or not filename.startswith("custom-") or Path(filename).name != filename:
        return
    for directory in (BASE_DIR / "dist" / "mvps", BASE_DIR / "frontend" / "public" / "mvps"):
        (directory / filename).unlink(missing_ok=True)


def serialize_catalog_item(item):
    result = dict(item)
    result["sprite_url"] = f'/api/catalog/{item["id"]}/sprite' if item.get("sprite_filename") else None
    return result


def fetch_mvps():
    if not BRIGHTDATA_API_TOKEN:
        raise RuntimeError("Falta configurar BRIGHTDATA_API_TOKEN")

    headers = {
        "Authorization": f"Bearer {BRIGHTDATA_API_TOKEN}",
        "Content-Type": "application/json",
    }
    trigger_url = (
        "https://api.brightdata.com/dca/trigger"
        f"?collector={BRIGHTDATA_COLLECTOR_ID}&queue_next=1"
    )
    trigger = BRIGHTDATA_HTTP.request(
        "POST",
        trigger_url,
        headers=headers,
        body=json.dumps([{"url": SAKURA_RANKING_URL}]).encode("utf-8"),
    )
    if trigger.status >= 400:
        raise RuntimeError(f"Bright Data no pudo iniciar la consulta (HTTP {trigger.status})")
    try:
        collection_id = json.loads(trigger.data.decode("utf-8")).get("collection_id")
    except (UnicodeDecodeError, json.JSONDecodeError, AttributeError) as error:
        raise RuntimeError("Bright Data devolvio una respuesta de inicio invalida") from error
    if not collection_id:
        raise RuntimeError("Bright Data no devolvio collection_id")

    deadline = time.monotonic() + BRIGHTDATA_RESULT_TIMEOUT_SECONDS
    dataset_url = f"https://api.brightdata.com/dca/dataset?id={collection_id}"
    while time.monotonic() < deadline:
        result = BRIGHTDATA_HTTP.request("GET", dataset_url, headers=headers)
        if result.status == 200:
            try:
                payload = json.loads(result.data.decode("utf-8"))
            except (UnicodeDecodeError, json.JSONDecodeError) as error:
                raise RuntimeError("Bright Data devolvio un JSON invalido") from error
            if isinstance(payload, (list, dict)):
                records = payload if isinstance(payload, list) else [payload]
                rows = []
                for record in records:
                    if not isinstance(record, dict):
                        continue
                    nested = record.get("mvp_rankings")
                    if isinstance(nested, list):
                        rows.extend(nested)
                    elif all(key in record for key in ("event_time", "character", "mvp", "map")):
                        rows.append(record)
                return [
                    canonical_entry({
                        "event_time": str(row.get("event_time", "")).strip(),
                        "detected_at": str(row.get("event_time", "")).strip(),
                        "character": str(row.get("character", "")).strip(),
                        "mvp": str(row.get("mvp", "")).strip(),
                        "experience": str(row.get("experience", "")).strip(),
                        "map": str(row.get("map", "")).strip(),
                    })
                    for row in rows
                    if isinstance(row, dict) and row.get("event_time")
                ]
        elif result.status != 202:
            raise RuntimeError(f"Bright Data no pudo entregar el resultado (HTTP {result.status})")
        time.sleep(BRIGHTDATA_RESULT_POLL_SECONDS)

    raise RuntimeError("Bright Data tardo mas de 5 minutos en entregar el resultado")


def mvp_id(mvp):
    return "|".join(
        str(mvp.get(field, ""))
        for field in ("event_time", "character", "mvp", "experience", "map")
    )


def parse_sakura_time(value):
    return SAKURA_TZ.localize(datetime.strptime(value, "%Y-%m-%d %H:%M:%S"))


def timer_state(timer, now=None):
    now = now or datetime.now(SAKURA_TZ)
    result = dict(timer)
    exact_spawn_at = timer.get("exact_spawn_at")
    if exact_spawn_at:
        exact_time = parse_sakura_time(exact_spawn_at)
        seconds_until_exact = max(0, int((exact_time - now).total_seconds()))
        result["seconds_until_delay"] = seconds_until_exact
        result["seconds_until_max"] = seconds_until_exact
        result["in_delay_window"] = False
        result["is_alive"] = now >= exact_time
        return result

    delay_start = parse_sakura_time(timer["delay_window_start"])
    delay_end = parse_sakura_time(timer["delay_window_end"])
    result["seconds_until_delay"] = max(0, int((delay_start - now).total_seconds()))
    result["seconds_until_max"] = max(0, int((delay_end - now).total_seconds()))
    result["in_delay_window"] = delay_start <= now < delay_end
    result["is_alive"] = now >= delay_end
    return result


def create_or_replace_timer(mvp):
    config = database.get_config()
    mvp_config = config.get(mvp["mvp"])
    if not mvp_config or mvp["map"] not in mvp_config.get("maps", []):
        return None

    minimum = mvp_config["respawn_min_minutes"]
    maximum = mvp_config["respawn_max_minutes"]
    per_map = mvp_config.get("respawn_per_map", {}).get(mvp["map"])
    if per_map:
        minimum, maximum = per_map

    timer_id = f'{mvp["mvp"]}|{mvp["map"]}'
    shared_group = mvp_config.get("shared_spawn_group")
    timers = database.get_timers()
    kept = []
    removed = []
    killed_in_delay = False
    killed_while_alive = False

    for timer in timers:
        same_timer = timer.get("id") == timer_id
        same_group = shared_group and timer.get("shared_spawn_group") == shared_group
        if same_timer or same_group:
            state = timer_state(timer)
            killed_in_delay = killed_in_delay or state["in_delay_window"]
            killed_while_alive = killed_while_alive or state["is_alive"]
            removed.append(
                {
                    "mvp": timer.get("mvp"),
                    "map": timer.get("map"),
                    "was_in_delay": state["in_delay_window"],
                    "was_alive": state["is_alive"],
                }
            )
        else:
            kept.append(timer)

    killed_at = parse_sakura_time(mvp["detected_at"])
    delay_start = killed_at + timedelta(minutes=minimum)
    delay_end = killed_at + timedelta(minutes=maximum)
    new_timer = {
        "id": timer_id,
        "mvp": mvp["mvp"],
        "map": mvp["map"],
        "character": mvp["character"],
        "killed_at": mvp["detected_at"],
        "delay_window_start": delay_start.strftime("%Y-%m-%d %H:%M:%S"),
        "delay_window_end": delay_end.strftime("%Y-%m-%d %H:%M:%S"),
        "respawn_min_minutes": minimum,
        "respawn_max_minutes": maximum,
        "total_wait_seconds": minimum * 60,
        "killed_in_delay": killed_in_delay,
        "killed_while_alive": killed_while_alive,
        "shared_spawn_group": shared_group,
    }
    kept.append(new_timer)
    database.replace_timers(kept)
    return {"timer_created": True, "removed_timers": removed}


def check_for_new_mvps():
    current = fetch_mvps()
    if not current:
        return

    with data_lock:
        previous = database.get_last_check()
        previous_ids = {mvp_id(item) for item in previous if isinstance(item, dict)}

        new_mvps = [item for item in current if mvp_id(item) not in previous_ids]
        for mvp in reversed(new_mvps):
            database.add_history(mvp)
            # El timer se reconstruye aunque el evento ya exista en el historial.
            # Esto permite recuperar los timers al desplegar o reinicializar estado.
            create_or_replace_timer(mvp)
        database.replace_last_check(current)


def monitor_loop():
    while True:
        cycle_started = time.monotonic()
        try:
            check_for_new_mvps()
        except Exception as error:
            print(f"[{datetime.now(SAKURA_TZ):%Y-%m-%d %H:%M:%S}] Monitor error: {error}")
        time.sleep(max(1, POLL_SECONDS - (time.monotonic() - cycle_started)))


def start_monitor():
    global monitor_started
    if not monitor_started:
        monitor_started = True
        threading.Thread(target=monitor_loop, daemon=True, name="sakura-monitor").start()


@app.route("/")
@app.route("/testeo1")
@app.route("/testeo1/")
def index():
    react_index = BASE_DIR / "dist" / "index.html"
    response = send_from_directory(react_index.parent, react_index.name)
    response.headers["Cache-Control"] = "no-store, no-cache, must-revalidate, max-age=0"
    return response


@app.route("/mvps/<path:filename>")
@app.route("/testeo1/mvps/<path:filename>")
def mvp_sprite(filename):
    return send_from_directory(BASE_DIR / "dist" / "mvps", filename)


@app.route("/api/auth/status")
def auth_status():
    return jsonify({
        "authenticated": bool(session.get("is_admin")),
        "username": ADMIN_USERNAME if session.get("is_admin") else None,
        "csrf_token": session.get("csrf_token") if session.get("is_admin") else None,
    })


@app.route("/api/auth/login", methods=["POST"])
def admin_login():
    payload = request.get_json(silent=True) or {}
    username_ok = hmac.compare_digest(str(payload.get("username", "")), ADMIN_USERNAME)
    password_ok = valid_admin_password(payload.get("password", ""))
    if not username_ok or not password_ok:
        return jsonify({"error": "Usuario o contraseña incorrectos"}), 401
    session.clear()
    session.permanent = True
    session["is_admin"] = True
    session["csrf_token"] = secrets.token_urlsafe(32)
    return jsonify({
        "message": "Sesión iniciada correctamente",
        "authenticated": True,
        "username": ADMIN_USERNAME,
        "csrf_token": session["csrf_token"],
    })


@app.route("/api/auth/logout", methods=["POST"])
@admin_required
def admin_logout():
    session.clear()
    return jsonify({"message": "Sesión cerrada correctamente"})


@app.route("/api/catalog")
def catalog():
    with data_lock:
        items = [serialize_catalog_item(item) for item in database.get_catalog()]
    return jsonify({"mvps": items, "total": len(items)})


@app.route("/api/catalog", methods=["POST"])
@admin_required
def create_catalog_mvp():
    sprite_filename = None
    sprite_data = None
    try:
        data = validate_catalog_payload(catalog_payload())
        uploaded = save_uploaded_gif(request.files.get("sprite"))
        if uploaded:
            sprite_filename, sprite_data = uploaded
        with data_lock:
            item = database.create_mvp(data, sprite_filename, sprite_data)
        return jsonify({"message": "MVP agregado correctamente", "mvp": serialize_catalog_item(item)}), 201
    except ValueError as error:
        delete_custom_sprite(sprite_filename)
        return jsonify({"error": str(error)}), 400
    except sqlite3.IntegrityError:
        delete_custom_sprite(sprite_filename)
        return jsonify({"error": "Ya existe un MVP o mapa con esos datos"}), 409


@app.route("/api/catalog/<int:mvp_id>", methods=["PUT"])
@admin_required
def update_catalog_mvp(mvp_id):
    sprite_filename = None
    sprite_data = None
    try:
        data = validate_catalog_payload(catalog_payload())
        upload = request.files.get("sprite")
        uploaded = save_uploaded_gif(upload)
        if uploaded:
            sprite_filename, sprite_data = uploaded
        with data_lock:
            previous = database.get_catalog_item(mvp_id)
            if not previous:
                delete_custom_sprite(sprite_filename)
                return jsonify({"error": "El MVP no existe"}), 404
            item = database.update_mvp(
                mvp_id, data, sprite_filename, sprite_data,
                replace_sprite=bool(sprite_filename),
            )
        if sprite_filename:
            delete_custom_sprite(previous.get("sprite_filename"))
        return jsonify({"message": "MVP actualizado correctamente", "mvp": serialize_catalog_item(item)})
    except ValueError as error:
        delete_custom_sprite(sprite_filename)
        return jsonify({"error": str(error)}), 400
    except sqlite3.IntegrityError:
        delete_custom_sprite(sprite_filename)
        return jsonify({"error": "Ya existe un MVP o mapa con esos datos"}), 409


@app.route("/api/catalog/<int:mvp_id>", methods=["DELETE"])
@admin_required
def delete_catalog_mvp(mvp_id):
    with data_lock:
        previous = database.get_catalog_item(mvp_id)
        if not previous:
            return jsonify({"error": "El MVP no existe"}), 404
        sprite_filename = database.delete_mvp(mvp_id)
    delete_custom_sprite(sprite_filename)
    return jsonify({"message": "MVP eliminado correctamente"})


@app.route("/api/catalog/<int:mvp_id>/sprite")
def catalog_mvp_sprite(mvp_id):
    sprite = database.get_mvp_sprite(mvp_id)
    if not sprite:
        return jsonify({"error": "El MVP no tiene un GIF personalizado"}), 404
    filename, content = sprite
    return Response(
        content, mimetype="image/gif",
        headers={"Content-Disposition": f'inline; filename="{filename}"', "Cache-Control": "no-store"},
    )


@app.route("/api/timers/reset-one", methods=["POST"])
def reset_one_timer():
    timer_id = str((request.get_json(silent=True) or {}).get("id", "")).strip()
    if not timer_id:
        return jsonify({"error": "Falta identificar el timer"}), 400
    with data_lock:
        deleted = database.delete_timer(timer_id)
    if not deleted:
        return jsonify({"error": "El timer ya no existe"}), 404
    return jsonify({"message": "Timer reiniciado correctamente"})


@app.route("/api/history")
def history():
    with data_lock:
        entries = [
            canonical_entry(entry)
            for entry in database.get_history()
            if isinstance(entry, dict)
        ]
    return jsonify({"history": entries, "total": len(entries)})


@app.route("/api/timers")
def timers():
    with data_lock:
        entries = [timer_state(timer) for timer in database.get_timers()]
    return jsonify(
        {
            "timers": entries,
            "total": len(entries),
            "current_time": datetime.now(SAKURA_TZ).strftime("%Y-%m-%d %H:%M:%S"),
        }
    )


@app.route("/api/timers/exact-delay", methods=["POST"])
def set_exact_delay():
    payload = request.get_json(silent=True) or {}
    timer_id = str(payload.get("id", ""))
    try:
        delay_minutes = int(payload.get("minutes"))
    except (TypeError, ValueError):
        return jsonify({"error": "Ingresa una cantidad válida de minutos"}), 400

    with data_lock:
        entries = database.get_timers()
        selected = next((timer for timer in entries if timer.get("id") == timer_id), None)
        if not selected:
            return jsonify({"error": "El timer ya no está disponible"}), 404
        if selected.get("mvp") not in EXACT_DELAY_MVPS:
            return jsonify({"error": "El ajuste exacto no está disponible para este MVP"}), 400

        minimum = int(selected["respawn_min_minutes"])
        maximum = int(selected["respawn_max_minutes"])
        maximum_delay = maximum - minimum
        if delay_minutes < 0 or delay_minutes > maximum_delay:
            return jsonify({"error": f"El delay debe estar entre 0 y {maximum_delay} minutos"}), 400

        exact_total_minutes = minimum + delay_minutes
        exact_spawn_at = parse_sakura_time(selected["killed_at"]) + timedelta(minutes=exact_total_minutes)
        selected["exact_delay_minutes"] = delay_minutes
        selected["exact_spawn_at"] = exact_spawn_at.strftime("%Y-%m-%d %H:%M:%S")
        selected["total_wait_seconds"] = exact_total_minutes * 60
        database.replace_timers(entries)
        state = timer_state(selected)

    return jsonify({"message": "Hora exacta guardada", "timer": state})


@app.route("/api/all")
def all_mvps():
    try:
        entries = fetch_mvps()
        return jsonify({"mvps": entries, "count": len(entries)})
    except Exception as error:
        return jsonify({"error": str(error), "mvps": [], "count": 0}), 502


@app.route("/api/reset", methods=["POST"])
def reset():
    try:
        current = fetch_mvps()
    except Exception:
        current = database.get_last_check()
    with data_lock:
        database.replace_last_check(current)
        database.clear_timers()
    return jsonify({"message": "Timers reiniciados correctamente", "total_timers": 0})


database.initialize()
if os.environ.get("SAKURA_DISABLE_MONITOR") != "1":
    start_monitor()


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=False)
