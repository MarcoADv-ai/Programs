CREATE TABLE IF NOT EXISTS mvp_definitions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    respawn_min_minutes INTEGER NOT NULL CHECK (respawn_min_minutes >= 0),
    respawn_max_minutes INTEGER NOT NULL CHECK (respawn_max_minutes >= respawn_min_minutes),
    shared_spawn_group TEXT,
    sprite_filename TEXT,
    sprite_data BLOB,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS mvp_maps (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    mvp_id INTEGER NOT NULL REFERENCES mvp_definitions(id) ON DELETE CASCADE,
    map_name TEXT NOT NULL,
    respawn_min_minutes INTEGER NOT NULL CHECK (respawn_min_minutes >= 0),
    respawn_max_minutes INTEGER NOT NULL CHECK (respawn_max_minutes >= respawn_min_minutes),
    is_override INTEGER NOT NULL DEFAULT 0 CHECK (is_override IN (0, 1)),
    position INTEGER NOT NULL DEFAULT 0,
    UNIQUE (mvp_id, map_name)
);

CREATE TABLE IF NOT EXISTS kill_events (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    source_key TEXT NOT NULL UNIQUE,
    mvp_id INTEGER REFERENCES mvp_definitions(id) ON DELETE SET NULL,
    mvp_map_id INTEGER REFERENCES mvp_maps(id) ON DELETE SET NULL,
    event_time TEXT NOT NULL,
    detected_at TEXT NOT NULL,
    character_name TEXT NOT NULL,
    mvp_name TEXT NOT NULL,
    experience TEXT NOT NULL DEFAULT '',
    map_name TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS last_check_events (
    source_key TEXT PRIMARY KEY,
    event_time TEXT NOT NULL,
    detected_at TEXT NOT NULL,
    character_name TEXT NOT NULL,
    mvp_name TEXT NOT NULL,
    experience TEXT NOT NULL DEFAULT '',
    map_name TEXT NOT NULL,
    position INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS active_timers (
    id TEXT PRIMARY KEY,
    mvp_id INTEGER NOT NULL REFERENCES mvp_definitions(id),
    mvp_map_id INTEGER NOT NULL REFERENCES mvp_maps(id),
    mvp_name TEXT NOT NULL,
    map_name TEXT NOT NULL,
    character_name TEXT NOT NULL,
    killed_at TEXT NOT NULL,
    delay_window_start TEXT NOT NULL,
    delay_window_end TEXT NOT NULL,
    respawn_min_minutes INTEGER NOT NULL CHECK (respawn_min_minutes >= 0),
    respawn_max_minutes INTEGER NOT NULL CHECK (respawn_max_minutes >= respawn_min_minutes),
    total_wait_seconds INTEGER NOT NULL CHECK (total_wait_seconds >= 0),
    killed_in_delay INTEGER NOT NULL DEFAULT 0 CHECK (killed_in_delay IN (0, 1)),
    killed_while_alive INTEGER NOT NULL DEFAULT 0 CHECK (killed_while_alive IN (0, 1)),
    shared_spawn_group TEXT,
    exact_delay_minutes INTEGER CHECK (exact_delay_minutes IS NULL OR exact_delay_minutes >= 0),
    exact_spawn_at TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_kill_events_time ON kill_events(event_time DESC);
CREATE INDEX IF NOT EXISTS idx_active_timers_group ON active_timers(shared_spawn_group);
