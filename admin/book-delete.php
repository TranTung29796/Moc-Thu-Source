<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_admin();
if (!is_post()) redirect('admin/books.php');
verify_csrf();
$statement = db()->prepare('DELETE FROM books WHERE id = ?');
$statement->execute([(int) ($_POST['id'] ?? 0)]);
flash('success', 'Đã xóa sách khỏi hệ thống.');
redirect('admin/books.php');

