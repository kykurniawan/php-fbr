<?php
defined('RUN') or http_response_code(404) and die();

allowed_methods('get');

header('Content-Type: application/json');

echo json_encode([
    'message' => 'OK',
    'data' => fbr()->object('user')->all(),
]);
