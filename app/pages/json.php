<?php
defined('RUN') or http_response_code(404) and die();

allowed_methods('get');

response()->json([
    'message' => 'You can even return JSON!',
    'data' => [
        'foo' => 'bar',
        'baz' => 'qux',
    ],
]);
