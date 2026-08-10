-- PHP-FBR database schema
--
-- SQLite is the default driver (auto-created and auto-migrated by the app),
-- so no manual setup is needed for it. This file is a reference for manual
-- setups and for the MySQL driver.

-- SQLite
CREATE TABLE IF NOT EXISTS users (
    id TEXT PRIMARY KEY,
    name TEXT NOT NULL DEFAULT '',
    email TEXT NOT NULL DEFAULT '',
    phone TEXT NOT NULL DEFAULT ''
);

-- MySQL (uncomment when DB_CONNECTION=mysql)
-- CREATE TABLE IF NOT EXISTS users (
--     id VARCHAR(64) PRIMARY KEY,
--     name VARCHAR(255) NOT NULL DEFAULT '',
--     email VARCHAR(255) NOT NULL DEFAULT '',
--     phone VARCHAR(64) NOT NULL DEFAULT ''
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
