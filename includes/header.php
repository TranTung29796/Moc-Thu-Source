<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? APP_NAME;
$activePage = $activePage ?? '';
$flashMessage = take_flash();
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Mộc Thư - Nhà sách trực tuyến dành cho người yêu tri thức.">
  <title><?= e($pageTitle) ?> | <?= APP_NAME ?></title>
  <link rel="stylesheet" href="<?= url('assets/vendor/bootstrap/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>?v=<?= filemtime(__DIR__ . '/../public/assets/css/style.css') ?>">
</head>
<body class="<?= e($bodyClass ?? '') ?>">
<header class="site-header sticky-top">
  <nav class="navbar navbar-expand-lg" aria-label="Điều hướng chính">
    <div class="container">
      <a class="navbar-brand" href="<?= url('index.php') ?>" aria-label="Mộc Thư - Trang chủ"><span class="brand-mark">M</span><span>Mộc <strong>Thư</strong></span></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="Mở menu"><span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse" id="mainMenu">
        <ul class="navbar-nav mx-auto">
          <?php foreach ([
              'home' => ['index.php', 'Trang chủ'],
              'about' => ['about.php', 'Giới thiệu'],
              'books' => ['books.php', 'Sách'],
              'news' => ['news.php', 'Tin tức'],
              'contact' => ['contact.php', 'Liên hệ'],
          ] as $key => [$path, $label]): ?>
            <li class="nav-item"><a class="nav-link <?= $activePage === $key ? 'active' : '' ?>" href="<?= url($path) ?>"><?= $label ?></a></li>
          <?php endforeach; ?>
        </ul>
        <div class="header-actions">
          <?php if (current_user()): ?>
            <?php if (is_admin()): ?><a class="btn btn-sm btn-outline-dark" href="<?= url('admin/index.php') ?>">Quản trị</a><?php endif; ?>
            <span class="user-greeting">Chào, <?= e(current_user()['full_name']) ?></span>
            <a class="btn btn-sm btn-dark" href="<?= url('logout.php') ?>">Đăng xuất</a>
          <?php else: ?>
            <a class="btn btn-sm btn-link" href="<?= url('login.php') ?>">Đăng nhập</a>
            <a class="btn btn-sm btn-dark" href="<?= url('register.php') ?>">Đăng ký</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>
</header>
<?php if ($flashMessage): ?>
  <div class="toast-container position-fixed top-0 end-0 p-3 app-toast-wrap">
    <div class="toast show border-0 shadow" role="status" aria-live="polite"><div class="toast-body d-flex align-items-center justify-content-between gap-3"><span><?= e($flashMessage['message']) ?></span><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Đóng"></button></div></div>
  </div>
<?php endif; ?>
<main>
