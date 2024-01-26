<?php
defined('RUN') or http_response_code(404) and die();

class Session implements SessionInterface
{
    public function start(): void
    {
        session_start();
    }

    public function destroy(): void
    {
        session_destroy();
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, $default = null)
    {
        $data = $_SESSION[$key] ?? $default;

        if ($this->has('__flash') && in_array($key, $_SESSION['__flash'])) {
            $this->remove($key);
        }

        return $data;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function pull(string $key, $default = null)
    {
        $value = $this->get($key, $default);

        $this->remove($key);

        return $value;
    }

    public function flash(string $key, $value): void
    {
        $this->set($key, $value);

        if (!$this->has('__flash')) {
            $this->set('__flash', []);
        }

        $this->push('__flash', $key);
    }

    public function push(string $key, $value): void
    {
        if (!$this->has($key)) {
            $this->set($key, []);
        }

        $this->set($key, array_merge($this->get($key), [$value]));
    }
}
