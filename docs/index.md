---
layout: default
title: Home
nav_order: 1
---

# PHP-FBR Documentation

PHP-FBR is a tiny, file-based PHP micro-framework. Every file in `app/pages/`
is a route — the file path *is* the URL. No Composer, no npm, no build step:
clone it, point your document root at `public/`, and it runs.

## Documentation index

| Topic | Description |
| ----- | ----------- |
| [Getting Started](getting-started.md) | Requirements, installation, running with the built-in server or Apache |
| [Routing](routing.md) | How file paths become routes, dynamic `[params]`, HTTP method guards |
| [Request](request.md) | Reading query strings, POST bodies, JSON, files, cookies, headers |
| [Response](response.md) | Sending HTML, JSON, or raw content with custom status codes |
| [Session](session.md) | Session storage, flash messages, and helpers |
| [Middleware](middleware.md) | Global request middleware with per-route targeting |
| [Application](application.md) | Bootstrapping, object container, events, layouts, and error handling |

## Quick start

```bash
git clone https://github.com/kykurniawan/php-fbr.git
cd php-fbr
APP_URL=http://127.0.0.1:8000/ php -S 127.0.0.1:8000 -t public public/index.php
```

Then open <http://127.0.0.1:8000/>.

## Repository layout

```
public/            Web root (index.php front controller + .htaccess)
core/              The micro-framework (router, request, response, session, middleware)
app/
  app.php          Application bootstrap (configuration + object bindings)
  pages/           File-based routes — each file is a URL
  layouts/         Layout templates used with page_start()/page_end()
docs/              This documentation
```
