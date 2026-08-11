# Application

The application is bootstrapped in `app/app.php`, which returns a
configured `FBR` instance. `public/index.php` calls `->run()` on it.

## Bootstrap example

```php
$fbr = new FBR();

$fbr->setLayoutFilesDir(__DIR__ . "/layouts");
$fbr->setPageFilesDir(__DIR__ . "/pages");
$fbr->setBaseUrl(getenv("APP_URL") ?: "http://127.0.0.1/php-fbr/public/");
$fbr->setExceptionHandler(new ExceptionHandler);

return $fbr;
```

| Method                          | Description                              |
| ------------------------------- | ---------------------------------------- |
| `setPageFilesDir($dir)`         | Where route files live (required)        |
| `setLayoutFilesDir($dir)`       | Where layout templates live (required)   |
| `setBaseUrl($url)`              | Base URL for `url()` (required)          |
| `setExceptionHandler($handler)` | Custom error handler (see below)         |
| `setSessionHandler($handler)`   | Swap session backend (`SessionInterface`)|
| `middlewares(...$m)`            | Register global middleware               |

## Object container

Bind reusable objects at bootstrap and pull them out anywhere:

```php
$fbr->bindObject("db", new Database());

// in a page
$db = retrieve("db");
// or
$db = fbr()->object("db");
```

`retrieve()` throws `ObjectNotFoundException` for unknown names.

## URL generation

`url()` builds absolute URLs from the configured base URL:

```php
url("/users/42");  // with APP_URL=https://example.com/ -> https://example.com/users/42
```

`redirect()` sends a Location header and stops execution:

```php
redirect(url("/login"));  // 302
redirect(url("/"), 301);  // custom status
```

## Events

`on()` registers a single handler per event; `dispatch()` fires it:

```php
$fbr->on("user.created", function ($user) {
    // notify, log, ...
});

fbr()->dispatch("user.created", $user);
```

## Layouts

Pages can share a layout via output buffering:

```php
<?php page_start("main") ?>
<p>Page content…</p>
<?php page_end() ?>
```

`page_start()` starts buffering and remembers the layout; `page_end()`
captures everything since `page_start()` into the `PAGE_CONTENT` constant
and includes `layouts/main.php`:

```php
<!-- app/layouts/main.php -->
<!doctype html>
<html>
<body>
    <?= PAGE_CONTENT ?>
</body>
</html>
```

## Error handling

Built-in exception classes:

| Exception                        | HTTP | Meaning                         |
| -------------------------------- | ---- | ------------------------------- |
| `PageNotFoundException`          | 404  | No route matched                |
| `MethodNotAllowedException`      | 405  | HTTP method not allowed         |
| `AuthenticationException`        | 401  | Unauthenticated (convenience)   |
| `PageFileDoesNotExistException`  | 500  | Route file missing on disk      |
| `ObjectNotFoundException`        | 500  | `retrieve()` unknown object     |
| `InvalidPageReturnType`          | 500  | Page returned an invalid type   |
| `ConfigurationException`         | 500  | Missing required configuration  |

Any `Throwable` reaching `run()` is delegated to the exception handler.
The default `ExceptionHandler` sets the HTTP status from the exception
code, prints a short message for client errors (4xx), and full debug
details for server errors (5xx). Subclass it or write your own to return
HTML or JSON error pages.

## Helper reference

| Helper                    | Equivalent                     |
| ------------------------- | ------------------------------ |
| `fbr()`                   | `FBR::instance()`              |
| `request()`               | `fbr()->request()`             |
| `response()`              | new `Response(...)`            |
| `session()`               | `fbr()->session()`             |
| `url($route)`             | `fbr()->url($route)`           |
| `redirect($url, $code)`   | `fbr()->redirect(...)`         |
| `allowed_methods(...)`    | `fbr()->allowedMethods(...)`   |
| `page_start($layout)`     | `fbr()->pageStart($layout)`    |
| `page_end()`              | `fbr()->pageEnd()`             |
| `retrieve($name)`         | `fbr()->getObject($name)`      |
