<?php
define('RUN', true);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../core/loader.php';

$fbr = require_once __DIR__ . '/../app/loader.php';

$fbr->run();
