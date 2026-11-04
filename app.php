<?php
declare(strict_types=1);

define('APP_NAME', 'CivicLink');
define('BASE_URL', '/civiclink/public');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../app/Support/helpers.php';
