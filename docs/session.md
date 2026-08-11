---
layout: default
title: Session
nav_order: 6
---

# Session

`session()` wraps PHP native sessions with a small, ergonomic API. The
session is started automatically when the `FBR` application is constructed,
so it is always available in pages and middleware.

## Basic usage

```php
session()->set("user_id", 42);
$id = session()->get("user_id");     // 42
$id = session()->get("missing");     // null
$id = session()->get("missing", 0);  // 0

session()->has("user_id");    // true
session()->remove("user_id");
```

## Flash messages

`flash()` stores a value that is automatically removed after it is read
once — ideal for one-time success or error messages across redirects:

```php
// store
session()->flash("status", "User created!");

// next request — read and auto-delete
$status = session()->get("status");
```

## Other operations

| Method                   | Description                               |
| ------------------------ | ----------------------------------------- |
| `push($key, $value)`     | Append a value to an array in the session |
| `pull($key, $default)`   | Read and remove in one call               |
| `flush()`                | Clear the entire session                  |
| `destroy()`              | Destroy the session (`session_destroy()`) |

## Custom session handlers

FBR depends on the `SessionInterface` — swap in your own implementation
(for example database-backed sessions) at bootstrap:

```php
$fbr->setSessionHandler(new MyDatabaseSession());
```
