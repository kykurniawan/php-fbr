# PHP-FBR

A tiny file-based PHP micro-framework. Pages are plain PHP files in
`app/pages/` — the file path is the route. No Composer, no npm, no build
step: clone it, point your document root at `public/`, and it runs.

## Features

- File-based routing — every page file is a URL, including dynamic
  `[param]` segments
- HTTP method guards (`allowed_methods()`)
- Request, response, and session helpers with automatic JSON body parsing
- Global middleware with per-route targeting
- Layout templates via `page_start()` / `page_end()`
- Object container and event hooks
- PHP >= 8.0 only — works on cheap shared hosting

## Quick start

```bash
# from the project root
APP_URL=http://127.0.0.1:8000/ php -S 127.0.0.1:8000 -t public public/index.php
```

Open http://127.0.0.1:8000/ — you will see the demo page. Routes included:

| URL                 | File                                | Description             |
| ------------------- | ----------------------------------- | ----------------------- |
| `/`                 | `app/pages/index.php`               | Demo page (layout)      |
| `/{id}`             | `app/pages/[id].php`                | Reads an `id` parameter |
| `/{param1}/{param2}`| `app/pages/[param1]/[param2].php`   | Two route parameters    |
| `/json`             | `app/pages/json.php`                | JSON response example   |

## Requirements

- PHP >= 8.0
- Apache with mod_rewrite, the built-in PHP server, or Nginx
- No Composer, no build step

## Apache

Point your vhost document root to `public/`. The included `.htaccess`
rewrites all requests to `index.php`. Set `APP_URL` for link generation:

```apache
SetEnv APP_URL https://example.com/
```

## Configuration

| Variable   | Default                             | Description                  |
| ---------- | ----------------------------------- | ---------------------------- |
| `APP_URL`  | `http://127.0.0.1/php-fbr/public/`  | Base URL for generated links |

Further configuration (page dirs, base URL, middleware, object bindings)
happens in `app/app.php`.

## Structure

```
public/            Web root (index.php + .htaccess)
core/              The micro-framework (router, request, response, session, middleware)
app/
  app.php          Application bootstrap
  pages/           File-based routes
  layouts/         Layout templates (main.php)
docs/              Documentation (start at docs/README.md)
```

## Documentation

Full docs live in [`docs/`](docs/README.md): getting started, routing,
request, response, session, middleware, and application reference.
