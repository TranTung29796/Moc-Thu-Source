<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json; charset=utf-8');
$query = trim((string) ($_GET['q'] ?? ''));
if (mb_strlen($query) < 2) { echo json_encode(['items' => []], JSON_UNESCAPED_UNICODE); exit; }
$statement = db()->prepare("SELECT id, title, cover_image, price, sale_price FROM books WHERE status = 'published' AND (title LIKE ? OR isbn LIKE ?) ORDER BY featured DESC, title LIMIT 6");
$statement->execute(['%' . $query . '%', '%' . $query . '%']);
echo json_encode(['items' => $statement->fetchAll()], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

