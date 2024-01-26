<?php
defined('RUN') or http_response_code(404) and die();

require_once __DIR__ . '/modules/User.php';
require_once __DIR__ . '/middlewares/Authenticate.php';
require_once __DIR__ . '/middlewares/ExpectJson.php';
require_once __DIR__ . '/modules/ExceptionHandler.php';

$fbr = new FBR();

$fbr->setPageFilesDir(__DIR__ . '/pages');

$fbr->setBaseUrl('http://127.0.0.1/php-fbr/public/');

$fbr->bindObject('user', new User());

$fbr->middlewares(
    ExpectJson::make()->forRoutes(
        '/api/login',
        '/api/users',
    ),
    Authenticate::make()->forRoutes(
        '/api/users',
    ),
);

$fbr->setExceptionHandler(new ExceptionHandler);

return $fbr;
