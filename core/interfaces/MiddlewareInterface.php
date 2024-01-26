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
