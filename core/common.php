<?php
defined('RUN') or http_response_code(404) and die();

if (!function_exists('getallheaders')) {
    function getallheaders(): array
    {
        $headers = [];

        foreach ($_SERVER as $name => $value) {
            if (str_starts_with($name, 'HTTP_')) {
                $header = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $headers[$header] = $value;
            }
        }

        if (isset($_SERVER['CONTENT_TYPE'])) {
            $headers['Content-Type'] = $_SERVER['CONTENT_TYPE'];
        }

        if (isset($_SERVER['CONTENT_LENGTH'])) {
            $headers['Content-Length'] = $_SERVER['CONTENT_LENGTH'];
        }

        return $headers;
    }
}

if (!function_exists('fbr')) {
    function fbr(): FBR
    {
        return FBR::instance();
    }
}

if (!function_exists('request')) {
    function request(): Request
    {
        return fbr()->request();
    }
}

if (!function_exists('url')) {
    function url(string $route = '/'): string
    {
        return fbr()->url($route);
    }
}

if (!function_exists('allowed_methods')) {
    function allowed_methods(...$methods)
    {
        return fbr()->allowedMethods(...$methods);
    }
}

if (!function_exists('session')) {
    function session(): Session
    {
        return fbr()->session();
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url, int $statusCode = 302): void
    {
        fbr()->redirect($url, $statusCode);
    }
}

if (!function_exists('page_start')) {
    function page_start(string $layout): void
    {
        fbr()->pageStart($layout);
    }
}

if (!function_exists('page_end')) {
    function page_end(): void
    {
        fbr()->pageEnd();
    }
}
