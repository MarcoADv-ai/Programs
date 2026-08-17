import sqlite3
from pathlib import Path


def event_key(entry):
    return "|".join(
        str(entry.get(field, ""))
        for field in ("event_time", "character", "mvp", "experience", "map")
    )


class TrackerDatabase:
    def __init__(self, path):
        self.path = Path(path)

    def connect(self):
        connection = sqlite3.connect(self.path, timeout=10)
        connection.row_factory = sqlite3.Row
        connection.execute("PRAGMA foreign_keys = ON")
        connection.execute("PRAGMA busy_timeout = 10000")
        return connection

    def initialize(self):
        self.path.parent.mkdir(parents=True, exist_ok=True)
        schema_path = Path(__file__).resolve().parent / "schema.sql"
        seed_path = Path(__file__).resolve().parent / "seed.sql"
        with self.connect() as connection:
            connection.execute("PRAGMA journal_mode = WAL")
            connection.executescript(schema_path.read_text(encoding="utf-8"))
            columns = {
                row[1] for row in connection.execute("PRAGMA table_info(mvp_definitions)")
            }
            if "sprite_filename" not in columns:
                connection.execute("ALTER TABLE mvp_definitions ADD COLUMN sprite_filename TEXT")
            if "sprite_data" not in columns:
                connection.execute("ALTER TABLE mvp_definitions ADD COLUMN sprite_data BLOB")
            if connection.execute("SELECT COUNT(*) FROM mvp_definitions").fetchone()[0] == 0:
                connection.executescript(seed_path.read_text(encoding="utf-8"))
            connection.execute("PRAGMA optimize")

    def get_config(self):
        with self.connect() as connection:
            rows = connection.execute(
                """
                SELECT d.name, d.respawn_min_minutes AS base_min,
                       d.respawn_max_minutes AS base_max, d.shared_spawn_group,
                       m.map_name, m.respawn_min_minutes AS map_min,
                       m.respawn_max_minutes AS map_max, m.is_override
                FROM mvp_definitions d
                JOIN mvp_maps m ON m.mvp_id = d.id
                ORDER BY d.id, m.position
                """
            ).fetchall()
        config = {}
        for row in rows:
            item = config.setdefault(
                row["name"],
                {
                    "maps": [],
                    "respawn_min_minutes": row["base_min"],
                    "respawn_max_minutes": row["base_max"],
                },
            )
            if row["shared_spawn_group"]:
                item["shared_spawn_group"] = row["shared_spawn_group"]
            item["maps"].append(row["map_name"])
            if row["is_override"]:
                item.setdefault("respawn_per_map", {})[row["map_name"]] = [
                    row["map_min"], row["map_max"]
                ]
        return config

    def get_catalog(self):
        with self.connect() as connection:
            definitions = connection.execute(
                """
                SELECT id, name, respawn_min_minutes, respawn_max_minutes,
                       shared_spawn_group, sprite_filename
                FROM mvp_definitions ORDER BY name COLLATE NOCASE
                """
            ).fetchall()
            maps = connection.execute(
                """
                SELECT id, mvp_id, map_name, respawn_min_minutes,
                       respawn_max_minutes, is_override, position
                FROM mvp_maps ORDER BY mvp_id, position, id
                """
            ).fetchall()
        maps_by_mvp = {}
        for row in maps:
            maps_by_mvp.setdefault(row["mvp_id"], []).append(dict(row))
        return [
            {**dict(row), "maps": maps_by_mvp.get(row["id"], [])}
            for row in definitions
        ]

    def get_catalog_item(self, mvp_id):
        return next((item for item in self.get_catalog() if item["id"] == mvp_id), None)

    def create_mvp(self, data, sprite_filename=None, sprite_data=None):
        with self.connect() as connection:
            cursor = connection.execute(
                """
                INSERT INTO mvp_definitions
                    (name, respawn_min_minutes, respawn_max_minutes,
                    shared_spawn_group, sprite_filename, sprite_data, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
                """,
                (
                    data["name"], data["respawn_min_minutes"],
                    data["respawn_max_minutes"], data.get("shared_spawn_group"),
                    sprite_filename, sprite_data,
                ),
            )
            mvp_id = cursor.lastrowid
            self._save_catalog_maps(connection, mvp_id, data["maps"])
        return self.get_catalog_item(mvp_id)

    def update_mvp(self, mvp_id, data, sprite_filename=None, sprite_data=None, replace_sprite=False):
        with self.connect() as connection:
            current = connection.execute(
                "SELECT id, name, sprite_filename, sprite_data FROM mvp_definitions WHERE id = ?",
                (mvp_id,),
            ).fetchone()
            if not current:
                return None
            sprite = sprite_filename if replace_sprite else current["sprite_filename"]
            sprite_bytes = sprite_data if replace_sprite else current["sprite_data"]
            connection.execute(
                """
                UPDATE mvp_definitions
                SET name = ?, respawn_min_minutes = ?, respawn_max_minutes = ?,
                    shared_spawn_group = ?, sprite_filename = ?, sprite_data = ?,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
                """,
                (
                    data["name"], data["respawn_min_minutes"],
                    data["respawn_max_minutes"], data.get("shared_spawn_group"),
                    sprite, sprite_bytes, mvp_id,
                ),
            )
            existing_maps = {
                row["id"]: row for row in connection.execute(
                    "SELECT id, map_name FROM mvp_maps WHERE mvp_id = ?", (mvp_id,)
                ).fetchall()
            }
            kept_ids = self._save_catalog_maps(connection, mvp_id, data["maps"], existing_maps)
            removed_ids = set(existing_maps) - kept_ids
            if removed_ids:
                placeholders = ",".join("?" for _ in removed_ids)
                connection.execute(
                    f"DELETE FROM active_timers WHERE mvp_map_id IN ({placeholders})",
                    tuple(removed_ids),
                )
                connection.execute(
                    f"DELETE FROM mvp_maps WHERE id IN ({placeholders})",
                    tuple(removed_ids),
                )
            current_maps = connection.execute(
                """
                SELECT id, map_name, respawn_min_minutes, respawn_max_minutes
                FROM mvp_maps WHERE mvp_id = ?
                """,
                (mvp_id,),
            ).fetchall()
            for item in current_maps:
                connection.execute(
                    """
                    UPDATE active_timers
                    SET id = ?, mvp_name = ?, map_name = ?,
                        respawn_min_minutes = ?, respawn_max_minutes = ?,
                        delay_window_start = datetime(killed_at, '+' || ? || ' minutes'),
                        delay_window_end = datetime(killed_at, '+' || ? || ' minutes'),
                        total_wait_seconds = ? * 60, exact_delay_minutes = NULL,
                        exact_spawn_at = NULL, updated_at = CURRENT_TIMESTAMP
                    WHERE mvp_id = ? AND mvp_map_id = ?
                    """,
                    (
                        f'{data["name"]}|{item["map_name"]}', data["name"], item["map_name"],
                        item["respawn_min_minutes"], item["respawn_max_minutes"],
                        item["respawn_min_minutes"], item["respawn_max_minutes"],
                        item["respawn_min_minutes"], mvp_id, item["id"],
                    ),
                )
        return self.get_catalog_item(mvp_id)

    def _save_catalog_maps(self, connection, mvp_id, maps, existing_maps=None):
        existing_maps = existing_maps or {}
        kept_ids = set()
        for position, item in enumerate(maps):
            map_id = item.get("id")
            values = (
                item["map_name"], item["respawn_min_minutes"],
                item["respawn_max_minutes"], int(item.get("is_override", False)),
                position,
            )
            if map_id and map_id in existing_maps:
                connection.execute(
                    """
                    UPDATE mvp_maps SET map_name = ?, respawn_min_minutes = ?,
                        respawn_max_minutes = ?, is_override = ?, position = ?
                    WHERE id = ? AND mvp_id = ?
                    """,
                    (*values, map_id, mvp_id),
                )
                kept_ids.add(map_id)
            else:
                cursor = connection.execute(
                    """
                    INSERT INTO mvp_maps
                        (mvp_id, map_name, respawn_min_minutes,
                         respawn_max_minutes, is_override, position)
                    VALUES (?, ?, ?, ?, ?, ?)
                    """,
                    (mvp_id, *values),
                )
                kept_ids.add(cursor.lastrowid)
        return kept_ids

    def delete_mvp(self, mvp_id):
        with self.connect() as connection:
            row = connection.execute(
                "SELECT sprite_filename FROM mvp_definitions WHERE id = ?", (mvp_id,)
            ).fetchone()
            if not row:
                return None
            connection.execute("DELETE FROM active_timers WHERE mvp_id = ?", (mvp_id,))
            connection.execute("DELETE FROM mvp_definitions WHERE id = ?", (mvp_id,))
            return row["sprite_filename"]

    def get_mvp_sprite(self, mvp_id):
        with self.connect() as connection:
            row = connection.execute(
                "SELECT sprite_filename, sprite_data FROM mvp_definitions WHERE id = ?",
                (mvp_id,),
            ).fetchone()
        return (row["sprite_filename"], row["sprite_data"]) if row and row["sprite_data"] else None

    def delete_timer(self, timer_id):
        with self.connect() as connection:
            cursor = connection.execute("DELETE FROM active_timers WHERE id = ?", (timer_id,))
            return cursor.rowcount > 0

    def _resolve_relations(self, connection, mvp_name, map_name):
        row = connection.execute(
            """
            SELECT d.id AS mvp_id, m.id AS map_id
            FROM mvp_definitions d
            LEFT JOIN mvp_maps m ON m.mvp_id = d.id AND m.map_name = ?
            WHERE d.name = ?
            """,
            (map_name, mvp_name),
        ).fetchone()
        return (row["mvp_id"], row["map_id"]) if row else (None, None)

    def _event_values(self, connection, entry):
        mvp_id, map_id = self._resolve_relations(connection, entry.get("mvp", ""), entry.get("map", ""))
        event_time = str(entry.get("event_time") or entry.get("detected_at") or "")
        detected_at = str(entry.get("detected_at") or event_time)
        return (
            event_key(entry), mvp_id, map_id, event_time, detected_at,
            str(entry.get("character", "")), str(entry.get("mvp", "")),
            str(entry.get("experience", "")), str(entry.get("map", "")),
        )

    def _insert_history(self, connection, entries):
        if not isinstance(entries, list):
            return
        for entry in entries:
            if isinstance(entry, dict):
                connection.execute(
                    """
                    INSERT OR IGNORE INTO kill_events
                        (source_key, mvp_id, mvp_map_id, event_time, detected_at,
                         character_name, mvp_name, experience, map_name)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                    """,
                    self._event_values(connection, entry),
                )

    def add_history(self, entry):
        with self.connect() as connection:
            before = connection.total_changes
            self._insert_history(connection, [entry])
            return connection.total_changes > before

    def get_history(self):
        with self.connect() as connection:
            rows = connection.execute(
                """
                SELECT event_time, detected_at, character_name AS character,
                       mvp_name AS mvp, experience, map_name AS map
                FROM kill_events ORDER BY id
                """
            ).fetchall()
        return [dict(row) for row in rows]

    def _replace_last_check(self, connection, entries):
        connection.execute("DELETE FROM last_check_events")
        for position, entry in enumerate(entries):
            if not isinstance(entry, dict):
                continue
            values = self._event_values(connection, entry)
            connection.execute(
                """
                INSERT INTO last_check_events
                    (source_key, event_time, detected_at, character_name,
                     mvp_name, experience, map_name, position)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                """,
                (values[0], values[3], values[4], values[5], values[6], values[7], values[8], position),
            )

    def replace_last_check(self, entries):
        with self.connect() as connection:
            self._replace_last_check(connection, entries)

    def get_last_check(self):
        with self.connect() as connection:
            rows = connection.execute(
                """
                SELECT event_time, detected_at, character_name AS character,
                       mvp_name AS mvp, experience, map_name AS map
                FROM last_check_events ORDER BY position
                """
            ).fetchall()
        return [dict(row) for row in rows]

    def _replace_timers(self, connection, entries):
        connection.execute("DELETE FROM active_timers")
        for timer in entries:
            if not isinstance(timer, dict):
                continue
            mvp_id, map_id = self._resolve_relations(connection, timer.get("mvp", ""), timer.get("map", ""))
            if mvp_id is None or map_id is None:
                continue
            connection.execute(
                """
                INSERT INTO active_timers
                    (id, mvp_id, mvp_map_id, mvp_name, map_name, character_name,
                     killed_at, delay_window_start, delay_window_end,
                     respawn_min_minutes, respawn_max_minutes, total_wait_seconds,
                     killed_in_delay, killed_while_alive, shared_spawn_group,
                     exact_delay_minutes, exact_spawn_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
                """,
                (
                    timer["id"], mvp_id, map_id, timer["mvp"], timer["map"],
                    timer.get("character", ""), timer["killed_at"],
                    timer["delay_window_start"], timer["delay_window_end"],
                    int(timer["respawn_min_minutes"]), int(timer["respawn_max_minutes"]),
                    int(timer["total_wait_seconds"]), int(bool(timer.get("killed_in_delay"))),
                    int(bool(timer.get("killed_while_alive"))), timer.get("shared_spawn_group"),
                    timer.get("exact_delay_minutes"), timer.get("exact_spawn_at"),
                ),
            )

    def replace_timers(self, entries):
        with self.connect() as connection:
            self._replace_timers(connection, entries)

    def get_timers(self):
        with self.connect() as connection:
            rows = connection.execute(
                """
                SELECT id, mvp_name AS mvp, map_name AS map,
                       character_name AS character, killed_at, delay_window_start,
                       delay_window_end, respawn_min_minutes, respawn_max_minutes,
                       total_wait_seconds, killed_in_delay, killed_while_alive,
                       shared_spawn_group, exact_delay_minutes, exact_spawn_at
                FROM active_timers ORDER BY created_at, id
                """
            ).fetchall()
        timers = []
        for row in rows:
            timer = dict(row)
            timer["killed_in_delay"] = bool(timer["killed_in_delay"])
            timer["killed_while_alive"] = bool(timer["killed_while_alive"])
            if timer["exact_delay_minutes"] is None:
                timer.pop("exact_delay_minutes")
            if timer["exact_spawn_at"] is None:
                timer.pop("exact_spawn_at")
            timers.append(timer)
        return timers

    def clear_timers(self):
        with self.connect() as connection:
            connection.execute("DELETE FROM active_timers")
