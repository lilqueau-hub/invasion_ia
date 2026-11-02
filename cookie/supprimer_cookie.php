<?php

session_start();

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires' => time() - 86400,
        'path' => $params['path'],
        'domain' => $params['domain'],
        'secure' => $params['secure'],
        'httpponly' => $params['httpponly'],
        'samesite' => $params['samesite'] ?? 'Lax'
    ]);
}

session_destroy();
?>