<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';

function require_login(): void
{
    if (!current_user()) {
        flash('warning', 'Vui lòng đăng nhập để tiếp tục.');
        redirect('login.php');
    }
}

function require_admin(): void
{
    require_login();
    if (!is_admin()) {
        http_response_code(403);
        exit('Bạn không có quyền truy cập trang quản trị.');
    }
}

