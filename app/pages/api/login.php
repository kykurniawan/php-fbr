<?php
defined('RUN') or http_response_code(404) and die();

allowed_methods('post');

header('Content-Type: application/json');

echo json_encode([
    'message' => 'Login success',
    'data' => request()->post(),
]);
