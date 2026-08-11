---
layout: default
title: Middleware
nav_order: 7
---

# Middleware

Middleware runs before a page is included. It can inspect or modify the
`Request`, then pass control to the next handler.

## Defining middleware

Implement `MiddlewareInterface`, or extend the `Middleware` base class:

```php
class Authenticate extends Middleware
{
    public function handle(Request $request, Closure $next, ...$params)
    {
        if (!session()->has("user_id")) {
            redirect(url("/login"));
        }

        return $next($request);
    }
}
```

The `$next` closure passes the (possibly modified) request to the rest of
the chain; the page runs only after it is called.

## Registering middleware

Register middleware in `app/app.php` before `run()`:

```php
$fbr->middlewares(
    Authenticate::make()->forRoutes("*")->except("/login"),
    ExpectJson::make()->forRoutes("/api/users", "/api/login"),
);
```

| Method                     | Description                                      |
| -------------------------- | ------------------------------------------------ |
| `forRoutes(...$routes)`    | Apply only to these exact routes; `*` = all      |
| `except(...$routes)`       | Skip these routes (checked per middleware)       |
| `make(...$params)`         | Create an instance; params are forwarded to `handle()` |

## Route matching

- `forRoutes("*")` applies the middleware to every route.
- `forRoutes("/users")` applies it only to the exact route `/users`.
- `except("/login")` exempts `/login` from an otherwise-global middleware.
- Matching is exact — `*` is the only wildcard; patterns like `/api/*` are
  not expanded, so list each route explicitly.
- Params given to `make()` are appended to the `handle()` variadic
  `$params`.

Multiple middleware run in registration order, each receiving the request
returned by the previous one.

## Example: JSON-only endpoint

```php
class ExpectJson extends Middleware
{
    public function handle(Request $request, Closure $next, ...$params)
    {
        if ($request->header("accept") !== "application/json") {
            response()->statusCode(406)->json(["error" => "JSON expected"]);
        }

        return $next($request);
    }
}
```
