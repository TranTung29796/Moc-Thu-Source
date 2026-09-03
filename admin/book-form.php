<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$id = max(0, (int) ($_GET['id'] ?? $_POST['id'] ?? 0));
$book = null;
if ($id) { $statement = db()->prepare('SELECT b.*, ba.author_id FROM books b LEFT JOIN book_authors ba ON ba.book_id = b.id WHERE b.id = ? LIMIT 1'); $statement->execute([$id]); $book = $statement->fetch(); if (!$book) { http_response_code(404); exit('Không tìm thấy sách.'); } }
$categories = db()->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$authors = db()->query('SELECT id, name FROM authors ORDER BY name')->fetchAll();
$errors = [];
if (is_post()) {
    verify_csrf();
    $data = [
        'category_id' => (int) ($_POST['category_id'] ?? 0), 'author_id' => (int) ($_POST['author_id'] ?? 0),
        'title' => trim((string) ($_POST['title'] ?? '')), 'isbn' => trim((string) ($_POST['isbn'] ?? '')),
        'publisher' => trim((string) ($_POST['publisher'] ?? '')), 'published_year' => (int) ($_POST['published_year'] ?? 0),
        'price' => (float) ($_POST['price'] ?? 0), 'sale_price' => ($_POST['sale_price'] ?? '') !== '' ? (float) $_POST['sale_price'] : null,
        'stock' => max(0, (int) ($_POST['stock'] ?? 0)), 'short_description' => trim((string) ($_POST['short_description'] ?? '')),
        'description' => trim((string) ($_POST['description'] ?? '')), 'featured' => isset($_POST['featured']) ? 1 : 0,
        'status' => in_array($_POST['status'] ?? '', ['published', 'draft'], true) ? $_POST['status'] : 'draft',
    ];
    if (mb_strlen($data['title']) < 2) $errors[] = 'Tên sách chưa hợp lệ.';
    if (!preg_match('/^[0-9-]{10,20}$/', $data['isbn'])) $errors[] = 'ISBN phải gồm 10–20 chữ số hoặc dấu gạch.';
    if ($data['category_id'] < 1 || $data['author_id'] < 1) $errors[] = 'Vui lòng chọn danh mục và tác giả.';
    if ($data['price'] <= 0 || $data['published_year'] < 1900) $errors[] = 'Giá hoặc năm xuất bản chưa hợp lệ.';
    try { $cover = upload_image($_FILES['cover_image'] ?? [], $book['cover_image'] ?? '/assets/cover-literary.png'); } catch (RuntimeException $error) { $errors[] = $error->getMessage(); $cover = $book['cover_image'] ?? '/assets/cover-literary.png'; }
    if (!$errors) {
        $slug = slugify($data['title']) . ($id ? '-' . $id : '-' . substr(bin2hex(random_bytes(3)), 0, 6));
        db()->beginTransaction();
        try {
            if ($id) {
                $sql = 'UPDATE books SET category_id=?, title=?, slug=?, isbn=?, publisher=?, published_year=?, price=?, sale_price=?, stock=?, cover_image=?, short_description=?, description=?, featured=?, status=? WHERE id=?';
                db()->prepare($sql)->execute([$data['category_id'],$data['title'],$slug,$data['isbn'],$data['publisher'],$data['published_year'],$data['price'],$data['sale_price'],$data['stock'],$cover,$data['short_description'],$data['description'],$data['featured'],$data['status'],$id]);
                db()->prepare('DELETE FROM book_authors WHERE book_id = ?')->execute([$id]);
            } else {
                $sql = 'INSERT INTO books (category_id,title,slug,isbn,publisher,published_year,price,sale_price,stock,cover_image,short_description,description,featured,status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
                db()->prepare($sql)->execute([$data['category_id'],$data['title'],$slug,$data['isbn'],$data['publisher'],$data['published_year'],$data['price'],$data['sale_price'],$data['stock'],$cover,$data['short_description'],$data['description'],$data['featured'],$data['status']]);
                $id = (int) db()->lastInsertId();
            }
            db()->prepare('INSERT INTO book_authors (book_id, author_id) VALUES (?, ?)')->execute([$id, $data['author_id']]);
            db()->commit(); flash('success', 'Đã lưu thông tin sách.'); redirect('admin/books.php');
        } catch (Throwable $error) { db()->rollBack(); $errors[] = 'Không thể lưu sách. ISBN có thể đã tồn tại.'; }
    }
    $book = array_merge($book ?: [], $data);
}
$pageTitle = $id ? 'Sửa sách' : 'Thêm sách'; require __DIR__ . '/../includes/header.php';
?>
<section class="admin-page"><div class="container"><div class="admin-heading"><div><p class="eyebrow">CRUD sách</p><h1><?= $id ? 'Sửa thông tin sách' : 'Thêm sách mới' ?></h1></div><a class="btn btn-outline-dark" href="<?= url('admin/books.php') ?>">Quay lại</a></div><section class="admin-panel form-admin-panel"><?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?><form method="post" enctype="multipart/form-data" class="needs-validation" novalidate><?= csrf_field() ?><input type="hidden" name="id" value="<?= $id ?>"><div class="row g-3"><div class="col-md-8"><label class="form-label">Tên sách</label><input class="form-control" name="title" required value="<?= e($book['title'] ?? '') ?>"></div><div class="col-md-4"><label class="form-label">ISBN</label><input class="form-control" name="isbn" required pattern="[0-9-]{10,20}" value="<?= e($book['isbn'] ?? '') ?>"></div><div class="col-md-6"><label class="form-label">Danh mục</label><select class="form-select" name="category_id" required><option value="">Chọn danh mục</option><?php foreach ($categories as $item): ?><option value="<?= $item['id'] ?>" <?= (int) ($book['category_id'] ?? 0) === (int) $item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option><?php endforeach; ?></select></div><div class="col-md-6"><label class="form-label">Tác giả</label><select class="form-select" name="author_id" required><option value="">Chọn tác giả</option><?php foreach ($authors as $item): ?><option value="<?= $item['id'] ?>" <?= (int) ($book['author_id'] ?? 0) === (int) $item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option><?php endforeach; ?></select></div><div class="col-md-4"><label class="form-label">Nhà xuất bản</label><input class="form-control" name="publisher" required value="<?= e($book['publisher'] ?? '') ?>"></div><div class="col-md-2"><label class="form-label">Năm</label><input class="form-control" type="number" name="published_year" min="1900" max="<?= date('Y') + 1 ?>" required value="<?= e((string) ($book['published_year'] ?? date('Y'))) ?>"></div><div class="col-md-2"><label class="form-label">Tồn kho</label><input class="form-control" type="number" name="stock" min="0" required value="<?= e((string) ($book['stock'] ?? 0)) ?>"></div><div class="col-md-2"><label class="form-label">Giá</label><input class="form-control" type="number" name="price" min="1000" required value="<?= e((string) ($book['price'] ?? '')) ?>"></div><div class="col-md-2"><label class="form-label">Giá giảm</label><input class="form-control" type="number" name="sale_price" min="0" value="<?= e((string) ($book['sale_price'] ?? '')) ?>"></div><div class="col-12"><label class="form-label">Mô tả ngắn</label><input class="form-control" name="short_description" maxlength="255" required value="<?= e($book['short_description'] ?? '') ?>"></div><div class="col-12"><label class="form-label">Nội dung</label><textarea class="form-control" name="description" rows="5" required><?= e($book['description'] ?? '') ?></textarea></div><div class="col-md-6"><label class="form-label">Ảnh bìa (JPG/PNG/WEBP, tối đa 3 MB)</label><input class="form-control" type="file" name="cover_image" accept="image/jpeg,image/png,image/webp"></div><div class="col-md-3"><label class="form-label">Trạng thái</label><select class="form-select" name="status"><option value="published" <?= ($book['status'] ?? '') === 'published' ? 'selected' : '' ?>>Đang bán</option><option value="draft" <?= ($book['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Bản nháp</option></select></div><div class="col-md-3 d-flex align-items-end"><label class="form-check mb-2"><input class="form-check-input" type="checkbox" name="featured" value="1" <?= !empty($book['featured']) ? 'checked' : '' ?>><span class="form-check-label">Sách nổi bật</span></label></div><div class="col-12"><button class="btn btn-dark btn-lg" type="submit">Lưu sách</button></div></div></form></section></div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>

