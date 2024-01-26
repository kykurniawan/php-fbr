<?php
defined('RUN') or http_response_code(404) and die();

class Request
{
    protected array $get = [];
    protected array $post = [];
    protected array $files = [];
    protected array $server = [];
    protected array $cookie = [];
    protected array $parameters = [];
    protected array $headers = [];
    protected array $locals = [];

    public function __construct(array $get, array $post, array $files, array $server, array $cookie, array $parameters = [], array $headers = [])
    {
        $this->get = $get;
        $this->post = $post;
        $this->files = $files;
        $this->server = $server;
        $this->cookie = $cookie;
        $this->parameters = $parameters;
        $this->headers = $headers;
    }

    public function get(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->get;
        }

        return $this->get[$key] ?? $default;
    }

    public function post(?string $key = null, $default = null)
    {
        $contentType = $this->header('content-type');

        if ($contentType === 'application/json') {
            $post = json_decode(file_get_contents('php://input'), true);
            if (is_array($post)) {
                $this->post = $post;
            }
        }

        if ($key === null) {
            return $this->post;
        }

        return $this->post[$key] ?? $default;
    }

    public function cookie(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->cookie;
        }

        return $this->cookie[$key] ?? $default;
    }

    public function file(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->files;
        }

        return $this->files[$key] ?? $default;
    }

    public function method($uppercase = false)
    {
        $method = $this->server['REQUEST_METHOD'];

        if ($uppercase) {
            return strtoupper($method);
        }

        return strtolower($method);
    }

    public function url()
    {
        $scheme = $this->server['REQUEST_SCHEME'];
        $host = $this->server['HTTP_HOST'];
        if (isset($this->server['PORT'])) {
            $host .= ':' . $this->server['PORT'];
        }
        $uri = $this->server['REQUEST_URI'];

        $url = $scheme . '://' . $host . $uri;

        return $url;
    }

    public function parameter(string $key, $default = null)
    {
        return $this->parameters[$key] ?? $default;
    }

    public function setLocal(string $key, mixed $value): self
    {
        $this->locals[$key] = $value;

        return $this;
    }

    public function getLocal(string $key, mixed $default = null): mixed
    {
        return $this->locals[$key] ?? $default;
    }

    public function header(?string $key = null)
    {
        if ($key === null) {
            return $this->headers;
        }

        $key = strtolower($key);

        $this->headers = array_change_key_case($this->headers, CASE_LOWER);

        return $this->headers[$key] ?? null;
    }
}
