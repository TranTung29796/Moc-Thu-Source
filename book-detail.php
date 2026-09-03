<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
$id = max(1, (int) ($_GET['id'] ?? 0));
$statement = db()->prepare("SELECT b.*, c.name AS category_name, GROUP_CONCAT(a.name SEPARATOR ', ') AS author_names FROM books b JOIN categories c ON c.id = b.category_id LEFT JOIN book_authors ba ON ba.book_id = b.id LEFT JOIN authors a ON a.id = ba.author_id WHERE b.id = ? AND b.status = 'published' GROUP BY b.id");
$statement->execute([$id]);
$book = $statement->fetch();
if (!$book) { http_response_code(404); exit('Không tìm thấy sách.'); }
$pageTitle = $book['title']; $activePage = 'books';
require __DIR__ . '/includes/header.php';
?>
<section class="section-space"><div class="container"><a class="back-link" href="<?= url('books.php') ?>">← Quay lại danh mục</a><div class="book-detail-grid"><div class="detail-cover"><img src="<?= e($book['cover_image']) ?>" alt="Bìa sách <?= e($book['title']) ?>"></div><article><p class="eyebrow"><?= e($book['category_name']) ?></p><h1><?= e($book['title']) ?></h1><p class="detail-author">Tác giả: <strong><?= e($book['author_names']) ?></strong></p><p class="detail-lead"><?= e($book['short_description']) ?></p><div class="detail-price"><strong><?= money($book['sale_price'] ?: $book['price']) ?></strong><?php if ($book['sale_price']): ?><del><?= money($book['price']) ?></del><?php endif; ?></div><dl class="book-meta"><div><dt>ISBN</dt><dd><?= e($book['isbn']) ?></dd></div><div><dt>Nhà xuất bản</dt><dd><?= e($book['publisher']) ?></dd></div><div><dt>Năm xuất bản</dt><dd><?= (int) $book['published_year'] ?></dd></div><div><dt>Tồn kho</dt><dd><?= (int) $book['stock'] ?> cuốn</dd></div></dl><button class="btn btn-dark btn-lg" type="button" data-demo-action="add-book">Thêm vào danh sách yêu thích</button></article></div><article class="book-description"><p class="eyebrow">Giới thiệu sách</p><h2>Nội dung</h2><p><?= nl2br(e($book['description'])) ?></p></article></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>

