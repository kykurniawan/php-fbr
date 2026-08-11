---
layout: default
title: Getting Started
nav_order: 2
---

# Getting Started

## Requirements

- PHP >= 8.0 (built-in server, Apache + mod_php, or PHP-FPM)
- A web server — Apache with mod_rewrite is optional (only needed for URLs
  without a front-controller prefix)
- No Composer, no npm, no build step — upload and run

## Installation

Clone the repository and point your web server document root at `public/`:

```bash
git clone https://github.com/kykurniawan/php-fbr.git
```

The front controller (`public/index.php`) bootstraps the application and
dispatches the request. Page files are never accessed directly — a guard at
the top of every page (`defined('RUN') or die();`) returns 404 for direct file access.

## Running with the built-in PHP server

```bash
# from the project root
APP_URL=http://127.0.0.1:8000/ php -S 127.0.0.1:8000 -t public public/index.php
```

Open http://127.0.0.1:8000/ — you should see the demo page.

## Running with Apache

Point your vhost document root to `public/`. The included `.htaccess`
rewrites all requests to `index.php` so URLs work without the front
controller prefix:

```apache
DocumentRoot /var/www/php-fbr/public
SetEnv APP_URL https://example.com/
```

For Nginx, use `try_files $uri $uri/ /index.php$is_args$args;`.

## Configuration

FBR is configured in `app/app.php` — the application bootstrap file. The
only required setting is the base URL, which is also read from the
`APP_URL` environment variable:

| Variable   | Default                             | Description                  |
| ---------- | ----------------------------------- | ---------------------------- |
| `APP_URL`  | `http://127.0.0.1/php-fbr/public/`  | Base URL for generated links |
