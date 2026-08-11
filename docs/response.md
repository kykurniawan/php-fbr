# Response

`response()` builds an HTTP response and sends it. Unlike the page/layout
flow, the response helpers write the body directly and the page simply
stops.

## JSON

```php
response()->json([
    "message" => "Hello",
    "data"    => ["foo" => "bar"],
]);
```

## HTML and raw content

```php
response()->html("<h1>Hello</h1>");

response()
    ->contentType("text/plain")
    ->statusCode(201)
    ->header("X-Rate-Limit", "100")
    ->send("created");
```

## Builder methods

All setters return the same `Response` instance, so calls chain:

| Method                                     | Description                       |
| ------------------------------------------ | --------------------------------- |
| `response()`                               | New response (`text/html`, 200)   |
| `->contentType(string)`                    | Set `Content-Type`                |
| `->statusCode(int)`                        | Set HTTP status code              |
| `->header(string $key, string $value)`     | Add a response header             |
| `->send(string $content)`                  | Send raw content                  |
| `->html(string $content)`                  | Send HTML (`text/html`)           |
| `->json($data, $options = 0, $depth = 512)`| Send JSON (`application/json`)    |

`json()` passes `$options` and `$depth` straight to `json_encode()`, so
flags like `JSON_PRETTY_PRINT` work as expected.
