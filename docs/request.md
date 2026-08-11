# Request

`request()` returns the current `Request` object, which wraps PHP
superglobals plus route parameters and headers.

## Query string — `get()`

```php
$q = request()->get("q");           // $_GET["q"] ?? null
$all = request()->get();            // all $_GET
$page = request()->get("page", 1);  // with default
```

## POST body — `post()`

```php
$name = request()->post("name");
```

When the request has `Content-Type: application/json`, the JSON body is
decoded automatically, so `post()` works for JSON APIs too:

```php
// POST /api/users  {"name": "Rizky"}
request()->post("name"); // "Rizky"
```

## Route parameters — `parameter()`

Values captured by `[param]` route segments:

```php
// app/pages/[id].php
$id = request()->parameter("id");
```

## Other inputs

| Method                        | Returns                                    |
| ----------------------------- | ------------------------------------------ |
| `cookie($key, $default)`      | `$_COOKIE` value                           |
| `file($key, $default)`        | `$_FILES` entry                            |
| `header($key)`                | Request header, case-insensitive           |
| `method()`                    | HTTP method, lowercase (`get`, `post`, …)  |
| `method(true)`                | HTTP method, uppercase                     |
| `url()`                       | Full request URL (scheme://host/path?query)|

## Per-request storage — `setLocal()` / `getLocal()`

Store arbitrary values on the request object; useful for passing data
between middleware and pages:

```php
$request->setLocal("user", $user);
$user = $request->getLocal("user");
```
