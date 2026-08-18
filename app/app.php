<?php
defined('RUN') or http_response_code(404) and die();

require_once __DIR__ . '/../core/fbr.php';

$fbr = new FBR();

// Basic configuration
$fbr->setLayoutFilesDir(__DIR__ . '/layouts');
$fbr->setPageFilesDir(__DIR__ . '/pages');
$fbr->setBaseUrl(getenv('APP_URL') ?: 'http://127.0.0.1:8080/php-fbr/public/');
$fbr->setExceptionHandler(new ExceptionHandler);

// Object binding example
$fbr->bindObject('hello', new class {
    public function world(): string
    {
        return 'Hello, world!';
    }
});

$fbr->bindFunction('greet', function (FBR $fbr) {
    return 'Greeting: ' . $fbr->getObject('hello')->world();
});

return $fbr;