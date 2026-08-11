<?php
defined('RUN') or http_response_code(404) and die();

interface MiddlewareInterface
{
    public function handle(Request $request, Closure $next, ...$params);

    public function forRoutes(...$routes): MiddlewareInterface;

    public function getRoutes(): array;

    public function getParams(): array;

    public static function make(...$params): MiddlewareInterface;
}


class Middleware implements MiddlewareInterface
{
    protected array $routes = [];
    protected array $exceptRoutes = [];
    protected array $params = [];

    public function handle(Request $request, Closure $next, ...$params)
    {
        return $next($request);
    }

    public function forRoutes(...$routes): MiddlewareInterface
    {
        $this->routes = $routes;

        return $this;
    }

    public function except(...$routes): MiddlewareInterface
    {
        $this->exceptRoutes = $routes;

        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getExceptRoutes(): array
    {
        return $this->exceptRoutes;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public static function make(...$params): MiddlewareInterface
    {
        $middleware = new static;
        $middleware->params = $params;

        return $middleware;
    }
}
