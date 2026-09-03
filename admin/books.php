<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$query = trim((string) ($_GET['q'] ?? ''));
$page = max(1, (int) ($_GET['page'] ?? 1));
$params = [];
$where = '';
if ($query !== '') { $where = 'WHERE b.title LIKE :query OR b.isbn LIKE :query'; $params['query'] = '%' . $query . '%'; }
$count = db()->prepare("SELECT COUNT(*) FROM books b {$where}"); $count->execute($params);
$pager = pagination((int) $count->fetchColumn(), $page, 10);
$statement = db()->prepare("SELECT b.*, c.name AS category_name FROM books b JOIN categories c ON c.id = b.category_id {$where} ORDER BY b.updated_at DESC LIMIT 10 OFFSET " . $pager['offset']); $statement->execute($params); $books = $statement->fetchAll();
$pageTitle = 'Quản lý sách'; require __DIR__ . '/../includes/header.php';
?>
<section class="admin-page"><div class="container"><div class="admin-heading"><div><p class="eyebrow">Quản trị nội dung</p><h1>Quản lý sách</h1></div><a class="btn btn-dark" href="<?= url('admin/book-form.php') ?>">+ Thêm sách</a></div><nav class="admin-tabs"><a href="<?= url('admin/index.php') ?>">Tổng quan</a><a class="active" href="<?= url('admin/books.php') ?>">Quản lý sách</a><a href="<?= url('admin/contacts.php') ?>">Liên hệ</a></nav><section class="admin-panel"><form class="admin-toolbar" method="get"><input class="form-control" name="q" value="<?= e($query) ?>" placeholder="Tìm tên sách hoặc ISBN"><button class="btn btn-dark" type="submit">Tìm kiếm</button></form><div class="table-responsive"><table class="table admin-table align-middle"><thead><tr><th>Sách</th><th>Danh mục</th><th>Giá</th><th>Tồn kho</th><th>Trạng thái</th><th class="text-end">Thao tác</th></tr></thead><tbody><?php foreach ($books as $book): ?><tr><td><div class="table-book"><img src="<?= e($book['cover_image']) ?>" alt=""><div><strong><?= e($book['title']) ?></strong><small><?= e($book['isbn']) ?></small></div></div></td><td><?= e($book['category_name']) ?></td><td><?= money($book['sale_price'] ?: $book['price']) ?></td><td><?= (int) $book['stock'] ?></td><td><span class="status-pill"><?= $book['status'] === 'published' ? 'Đang bán' : 'Bản nháp' ?></span></td><td><div class="table-actions"><a class="btn btn-sm btn-outline-dark" href="<?= url('admin/book-form.php?id=' . (int) $book['id']) ?>">Sửa</a><form method="post" action="<?= url('admin/book-delete.php') ?>" onsubmit="return confirm('Xóa sách này khỏi hệ thống?')"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $book['id'] ?>"><button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button></form></div></td></tr><?php endforeach; ?></tbody></table></div><?php if ($pager['pages'] > 1): ?><nav class="pagination-wrap"><ul class="pagination"><?php for ($i = 1; $i <= $pager['pages']; $i++): ?><li class="page-item <?= $i === $pager['page'] ? 'active' : '' ?>"><a class="page-link" href="?q=<?= urlencode($query) ?>&page=<?= $i ?>"><?= $i ?></a></li><?php endfor; ?></ul></nav><?php endif; ?></section></div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>

