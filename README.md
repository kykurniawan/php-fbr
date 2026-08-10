# PHP-FBR

A tiny file-based PHP micro-framework with a users CRUD demo app and a small
JSON API. Pages are plain PHP files in `app/pages/` — the file path is the
route.

## Requirements

- PHP >= 8.0 (CLI or Apache/PHP-FPM)
- `pdo_sqlite` (default, zero-config) — or `pdo_mysql` for MySQL
- No Composer, no npm, no build step

## Quick start (built-in PHP server)

```bash
# from the project root
APP_URL=http://127.0.0.1:8000/ php -S 127.0.0.1:8000 -t public public/index.php
```

Open http://127.0.0.1:8000/ — you'll be redirected to the login page.
Default credentials: `admin` / `admin` (override with `ADMIN_USERNAME` /
`ADMIN_PASSWORD`).

The SQLite database is created automatically at `database/fbr.sqlite` on first
use, including the `users` table. No manual DB setup required.

## Apache

Point your vhost's document root to `public/`. The included `.htaccess`
rewrites all requests to `index.php`. Set `APP_URL` in your vhost env or
`SetEnv` directive, e.g.:

```apache
SetEnv APP_URL https://example.com/
```

## Configuration (environment variables)

| Variable          | Default                        | Description                                  |
| ----------------- | ------------------------------ | -------------------------------------------- |
| `APP_URL`         | `http://127.0.0.1/php-fbr/public/` | Base URL used to generate links          |
| `DB_CONNECTION`   | `sqlite`                       | `sqlite` or `mysql`                          |
| `DB_DATABASE`     | `database/fbr.sqlite`          | SQLite file path, or MySQL database name     |
| `DB_HOST`         | `127.0.0.1`                    | MySQL only                                   |
| `DB_PORT`         | `3306`                         | MySQL only                                   |
| `DB_USERNAME`     | `root`                         | MySQL only                                   |
| `DB_PASSWORD`     | *(empty)*                      | MySQL only                                   |
| `ADMIN_USERNAME`  | `admin`                        | Login username (web + API)                   |
| `ADMIN_PASSWORD`  | `admin`                        | Login password (web + API)                   |

MySQL schema reference: `database/schema.sql`.

## Routes

Web (all require login except `/login`):

| Route              | Method | Description            |
| ------------------ | ------ | ---------------------- |
| `/login`           | GET/POST | Login form / submit  |
| `/logout`          | POST   | Log out                |
| `/`                | GET    | Users list             |
| `/create`          | GET/POST | Create user form/submit |
| `/{id}`            | GET    | User detail            |
| `/edit/{id}`       | GET/POST | Edit user form/submit |
| `/{id}/delete`     | POST   | Delete user            |

API (JSON; login required for `/api/users`):

| Route          | Method | Description                      |
| -------------- | ------ | -------------------------------- |
| `/api/login`   | POST   | Authenticate, returns JSON       |
| `/api/users`   | GET    | List users (JSON)                |

## Testing

```bash
php tests/smoke.php
```

Runs the CRUD model against a throwaway SQLite file and exits non-zero on any
failure.

## Structure

```
public/            Web root (index.php + .htaccess)
core/              Micro-framework (router, request, session, middleware)
app/               Application layer
  pages/           File-based routes
  layouts/         Layout templates (main.php)
  middlewares/     Authenticate, ExpectJson
  modules/         User model, ExceptionHandler
database/          SQLite file (runtime) + schema.sql
tests/             Smoke test
```
