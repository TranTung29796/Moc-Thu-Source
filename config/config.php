<?php
declare(strict_types=1);

const APP_NAME = 'Mộc Thư';
const APP_URL = '';
const ITEMS_PER_PAGE = 8;

date_default_timezone_set('Asia/Ho_Chi_Minh');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => isset($_SERVER['HTTPS']),
    ]);
    session_start();
}

