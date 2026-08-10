<?php
defined('RUN') or http_response_code(404) and die();

allowed_methods('post');

header('Content-Type: application/json');

$username = request()->post('username');
$password = request()->post('password');

$adminUsername = getenv('ADMIN_USERNAME') ?: 'admin';
$adminPassword = getenv('ADMIN_PASSWORD') ?: 'admin';

if ($username === $adminUsername && $password === $adminPassword) {
    session()->set('uid', 1);

    echo json_encode([
        'message' => 'Login success',
        'data' => ['uid' => 1],
    ]);
    return;
}

http_response_code(401);
echo json_encode([
    'message' => 'Invalid username or password',
    'data' => [],
]);
