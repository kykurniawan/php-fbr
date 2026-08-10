<?php
defined('RUN') or http_response_code(404) and die();

interface SessionInterface
{
    public function start(): void;
    public function destroy(): void;
    public function set(string $key, $value): void;
    public function get(string $key, $default = null);
    public function has(string $key): bool;
    public function remove(string $key): void;
    public function pull(string $key, $default = null);
    public function flash(string $key, $value): void;
    public function push(string $key, $value): void;
    public function flush(): void;
}
