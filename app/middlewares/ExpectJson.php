<?php
defined('RUN') or http_response_code(404) and die();

class ExpectJson extends Middleware
{
    public function handle(Request $request, Closure $next, ...$params)
    {
        $request->setLocal('expectJson', true);

        return $next($request);
    }
}
