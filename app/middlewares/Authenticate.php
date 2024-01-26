<?php
defined('RUN') or http_response_code(404) and die();

class Authenticate extends Middleware
{
    public function handle(Request $request, Closure $next, ...$params)
    {
        $uid = session()->get('uid');
        
        if ($uid === null) {
            throw new AuthenticationException('Unauthenticated');
        }

        $request->setLocal('user', $uid);

        return $next($request);
    }
}
