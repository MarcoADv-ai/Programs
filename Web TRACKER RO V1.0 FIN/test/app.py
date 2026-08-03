from flask import Flask, jsonify, send_from_directory
from bs4 import BeautifulSoup
from datetime import datetime, timedelta
from pathlib import Path
import json
import threading
import time
import requests
import pytz


BASE_DIR = Path(__file__).resolve().parent
HISTORY_FILE = BASE_DIR / "mvps_history.json"
LAST_CHECK_FILE = BASE_DIR / "last_check.json"
TIMERS_FILE = BASE_DIR / "active_timers.json"
CONFIG_FILE = BASE_DIR / "mvp_config.json"
SAKURA_URL = "https://sakura-ro.com/?module=ranking&action=mvp"
# El ranking de Sakura RO publica sus timestamps en UTC-5.
SAKURA_TZ = pytz.timezone("America/Bogota")
POLL_SECONDS = 1

app = Flask(__name__, static_folder="dist/assets", static_url_path="/assets")
data_lock = threading.RLock()
monitor_started = False


def load_json(path, default):
    try:
        return json.loads(path.read_text(encoding="utf-8"))
    except (FileNotFoundError, json.JSONDecodeError, OSError):
        return default


def save_json(path, data):
    temporary = path.with_suffix(path.suffix + ".tmp")
    temporary.write_text(
        json.dumps(data, ensure_ascii=False, indent=2), encoding="utf-8"
    )
    temporary.replace(path)


def fetch_mvps():
    response = requests.get(
        SAKURA_URL,
        headers={"User-Agent": "Mozilla/5.0 (compatible; SakuraMVPTracker/3.0)"},
        timeout=10,
    )
    response.raise_for_status()
    soup = BeautifulSoup(response.text, "html.parser")
    table = soup.find("table")
    if not table:
        return []

    mvps = []
    for row in table.find_all("tr")[1:]:
        columns = row.find_all("td")
        if len(columns) < 5:
            continue
        event_time = columns[0].get_text(strip=True)
        mvps.append(
            {
                "event_time": event_time,
                "detected_at": event_time,
                "character": columns[1].get_text(strip=True),
                "mvp": columns[2].get_text(strip=True),
                "experience": columns[3].get_text(strip=True),
                "map": columns[4].get_text(strip=True),
            }
        )
    return mvps


def mvp_id(mvp):
    return "|".join(
        str(mvp.get(field, ""))
        for field in ("event_time", "character", "mvp", "experience", "map")
    )


def parse_sakura_time(value):
    return SAKURA_TZ.localize(datetime.strptime(value, "%Y-%m-%d %H:%M:%S"))


def timer_state(timer, now=None):
    now = now or datetime.now(SAKURA_TZ)
    delay_start = parse_sakura_time(timer["delay_window_start"])
    delay_end = parse_sakura_time(timer["delay_window_end"])
    result = dict(timer)
    result["seconds_until_delay"] = max(0, int((delay_start - now).total_seconds()))
    result["seconds_until_max"] = max(0, int((delay_end - now).total_seconds()))
    result["in_delay_window"] = delay_start <= now < delay_end
    result["is_alive"] = now >= delay_end
    return result


def create_or_replace_timer(mvp):
    config = load_json(CONFIG_FILE, {})
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
    timers = load_json(TIMERS_FILE, [])
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
    save_json(TIMERS_FILE, kept)
    return {"timer_created": True, "removed_timers": removed}


def check_for_new_mvps():
    current = fetch_mvps()
    if not current:
        return

    with data_lock:
        previous = load_json(LAST_CHECK_FILE, [])
        previous_ids = {mvp_id(item) for item in previous if isinstance(item, dict)}
        history = load_json(HISTORY_FILE, [])
        history_ids = {mvp_id(item) for item in history if isinstance(item, dict)}

        new_mvps = [item for item in current if mvp_id(item) not in previous_ids]
        for mvp in reversed(new_mvps):
            if mvp_id(mvp) not in history_ids:
                history.append(mvp)
            # El timer se reconstruye aunque el evento ya exista en el historial.
            # Esto permite recuperar los timers al desplegar o reinicializar estado.
            create_or_replace_timer(mvp)
        if new_mvps:
            save_json(HISTORY_FILE, history)
        save_json(LAST_CHECK_FILE, current)


def monitor_loop():
    while True:
        try:
            check_for_new_mvps()
        except Exception as error:
            print(f"[{datetime.now(SAKURA_TZ):%Y-%m-%d %H:%M:%S}] Monitor error: {error}")
        time.sleep(POLL_SECONDS)


def start_monitor():
    global monitor_started
    if not monitor_started:
        monitor_started = True
        threading.Thread(target=monitor_loop, daemon=True, name="sakura-monitor").start()


@app.route("/")
def index():
    react_index = BASE_DIR / "dist" / "index.html"
    response = send_from_directory(react_index.parent, react_index.name)
    response.headers["Cache-Control"] = "no-store, no-cache, must-revalidate, max-age=0"
    return response


@app.route("/mvps/<path:filename>")
def mvp_sprite(filename):
    return send_from_directory(BASE_DIR / "dist" / "mvps", filename)


@app.route("/api/history")
def history():
    with data_lock:
        entries = load_json(HISTORY_FILE, [])
    return jsonify({"history": entries, "total": len(entries)})


@app.route("/api/timers")
def timers():
    with data_lock:
        entries = [timer_state(timer) for timer in load_json(TIMERS_FILE, [])]
    return jsonify(
        {
            "timers": entries,
            "total": len(entries),
            "current_time": datetime.now(SAKURA_TZ).strftime("%Y-%m-%d %H:%M:%S"),
        }
    )


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
        current = load_json(LAST_CHECK_FILE, [])
    with data_lock:
        save_json(LAST_CHECK_FILE, current)
        save_json(TIMERS_FILE, [])
    return jsonify({"message": "Timers reiniciados correctamente", "total_timers": 0})


start_monitor()


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=False)
