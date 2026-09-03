<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$stats = [
    'books' => (int) db()->query('SELECT COUNT(*) FROM books')->fetchColumn(),
    'users' => (int) db()->query('SELECT COUNT(*) FROM users')->fetchColumn(),
    'contacts' => (int) db()->query("SELECT COUNT(*) FROM contacts WHERE status = 'new'")->fetchColumn(),
    'stock' => (int) db()->query('SELECT COALESCE(SUM(stock), 0) FROM books')->fetchColumn(),
];
$categoryStats = db()->query('SELECT c.name, COUNT(b.id) AS total FROM categories c LEFT JOIN books b ON b.category_id = c.id GROUP BY c.id ORDER BY total DESC')->fetchAll();
$latestBooks = db()->query('SELECT b.id, b.title, b.stock, b.price, b.status, c.name AS category_name FROM books b JOIN categories c ON c.id = b.category_id ORDER BY b.created_at DESC LIMIT 6')->fetchAll();
$pageTitle = 'Bảng điều khiển'; require __DIR__ . '/../includes/header.php';
?>
<section class="admin-page"><div class="container"><div class="admin-heading"><div><p class="eyebrow">Khu vực quản trị</p><h1>Tổng quan hệ thống</h1><p>Theo dõi dữ liệu sách, thành viên và liên hệ.</p></div><a class="btn btn-dark" href="<?= url('admin/book-form.php') ?>">+ Thêm sách</a></div><nav class="admin-tabs" aria-label="Điều hướng quản trị"><a class="active" href="<?= url('admin/index.php') ?>">Tổng quan</a><a href="<?= url('admin/books.php') ?>">Quản lý sách</a><a href="<?= url('admin/contacts.php') ?>">Liên hệ</a></nav><div class="stat-grid"><article><span>Sách</span><strong><?= $stats['books'] ?></strong><small>Tựa sách trong hệ thống</small></article><article><span>Kho</span><strong><?= $stats['stock'] ?></strong><small>Tổng số lượng tồn</small></article><article><span>Thành viên</span><strong><?= $stats['users'] ?></strong><small>Tài khoản đã đăng ký</small></article><article class="stat-alert"><span>Liên hệ mới</span><strong><?= $stats['contacts'] ?></strong><small>Đang chờ phản hồi</small></article></div><div class="admin-dashboard-grid"><section class="admin-panel"><div class="panel-heading"><h2>Sách mới cập nhật</h2><a href="<?= url('admin/books.php') ?>">Xem tất cả</a></div><div class="table-responsive"><table class="table admin-table"><thead><tr><th>Tựa sách</th><th>Danh mục</th><th>Tồn</th><th>Giá</th><th>Trạng thái</th></tr></thead><tbody><?php foreach ($latestBooks as $book): ?><tr><td><a href="<?= url('admin/book-form.php?id=' . (int) $book['id']) ?>"><strong><?= e($book['title']) ?></strong></a></td><td><?= e($book['category_name']) ?></td><td><?= (int) $book['stock'] ?></td><td><?= money($book['price']) ?></td><td><span class="status-pill"><?= $book['status'] === 'published' ? 'Đang bán' : 'Bản nháp' ?></span></td></tr><?php endforeach; ?></tbody></table></div></section><aside class="admin-panel"><div class="panel-heading"><h2>Theo danh mục</h2></div><ul class="category-stats"><?php foreach ($categoryStats as $item): ?><li><span><?= e($item['name']) ?></span><strong><?= (int) $item['total'] ?></strong></li><?php endforeach; ?></ul></aside></div></div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>

