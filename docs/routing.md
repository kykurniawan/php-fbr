---
layout: default
title: Routing
nav_order: 3
---

# Routing

FBR routes are plain PHP files in `app/pages/`. The file path is the route —
no route table to maintain.

## File path = URL

| File                                  | Route        |
| ------------------------------------- | ------------ |
| `app/pages/index.php`                 | `/`          |
| `app/pages/about.php`                 | `/about`     |
| `app/pages/contact/index.php`         | `/contact`   |
| `app/pages/blog/post.php`             | `/blog/post` |

`index.php` maps to the directory itself; every other file maps to its name
without the `.php` extension.

## Dynamic parameters

Segments wrapped in square brackets capture any value:

| File                                  | Route               | Example URL | `request()->parameter(...)` |
| `app/pages/[id].php`                  | `/[id]`             | `/42`       | `parameter('id')` → `42`     |
| `app/pages/[param1]/[param2].php`     | `/[param1]/[param2]`| `/a/b`      | `parameter('param1')` → `a`, `parameter('param2')` → `b` |

A `[param]` segment matches exactly one path segment — it never spans `/`.

## Route precedence

Routes are matched in order of *fewest parameters first*, so static routes
always win over parameterized ones when both could match. The first matching
route handles the request.

## HTTP methods

Use `allowed_methods()` at the top of a page to restrict which HTTP methods
it accepts:

```php
allowed_methods('get');          // GET only
allowed_methods('get', 'post');  // GET or POST
```

Calling the page with any other method throws `MethodNotAllowedException`
and responds with HTTP 405. Note that `allowed_methods()` itself does not
distinguish the page's action — check `request()->method()` inside the page
to branch between GET and POST handling.

## 404 and 405

When no route matches the request path, FBR throws `PageNotFoundException`
(HTTP 404). The default exception handler prints a short text message; see
[Application](application.md#error-handling) to customize responses.
