<?php
define('RUN', true);
ini_set('display_errors', 1);
error_reporting(E_ALL);

(require_once __DIR__ . '/../app/app.php')->run();
