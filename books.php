<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

$query = trim((string) ($_GET['q'] ?? ''));
$category = max(0, (int) ($_GET['category'] ?? 0));
$page = max(1, (int) ($_GET['page'] ?? 1));
$where = ["b.status = 'published'"];
$params = [];
if ($query !== '') { $where[] = '(b.title LIKE :query OR b.isbn LIKE :query OR b.publisher LIKE :query)'; $params['query'] = '%' . $query . '%'; }
if ($category > 0) { $where[] = 'b.category_id = :category'; $params['category'] = $category; }
$whereSql = implode(' AND ', $where);
$countStatement = db()->prepare("SELECT COUNT(*) FROM books b WHERE {$whereSql}");
$countStatement->execute($params);
$total = (int) $countStatement->fetchColumn();
$pager = pagination($total, $page);
$statement = db()->prepare("SELECT b.*, c.name AS category_name, GROUP_CONCAT(a.name SEPARATOR ', ') AS author_names FROM books b JOIN categories c ON c.id = b.category_id LEFT JOIN book_authors ba ON ba.book_id = b.id LEFT JOIN authors a ON a.id = ba.author_id WHERE {$whereSql} GROUP BY b.id ORDER BY b.featured DESC, b.created_at DESC LIMIT " . ITEMS_PER_PAGE . ' OFFSET ' . $pager['offset']);
$statement->execute($params);
$books = $statement->fetchAll();
$categories = db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$pageTitle = 'Danh mục sách'; $activePage = 'books';
require __DIR__ . '/includes/header.php';
?>
<section class="compact-hero"><div class="container"><p class="eyebrow">Thư viện Mộc Thư</p><h1>Tìm cuốn sách dành cho bạn</h1><p><?= $total ?> tựa sách đang được giới thiệu.</p></div></section>
<section class="section-space pt-4"><div class="container"><form class="catalog-toolbar" method="get" id="bookSearchForm"><label class="search-box"><span>⌕</span><input id="ajaxBookSearch" name="q" value="<?= e($query) ?>" placeholder="Tìm tên sách, ISBN, nhà xuất bản..." autocomplete="off"></label><label class="category-select"><span>Danh mục</span><select name="category" onchange="this.form.submit()"><option value="0">Tất cả</option><?php foreach ($categories as $item): ?><option value="<?= (int) $item['id'] ?>" <?= $category === (int) $item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option><?php endforeach; ?></select></label><button class="btn btn-dark" type="submit">Tìm kiếm</button></form><div id="ajaxSearchResults" class="ajax-results" hidden></div>
<div class="row g-4 mt-1" id="bookGrid"><?php if (!$books): ?><div class="col-12"><div class="empty-state"><h2>Chưa tìm thấy sách phù hợp</h2><p>Thử một từ khóa hoặc danh mục khác.</p></div></div><?php endif; ?><?php foreach ($books as $book): ?><div class="col-12 col-sm-6 col-lg-3"><?php require __DIR__ . '/includes/book-card.php'; ?></div><?php endforeach; ?></div>
<?php if ($pager['pages'] > 1): ?><nav class="pagination-wrap" aria-label="Phân trang sách"><ul class="pagination"><?php for ($i = 1; $i <= $pager['pages']; $i++): ?><li class="page-item <?= $i === $pager['page'] ? 'active' : '' ?>"><a class="page-link" href="?q=<?= urlencode($query) ?>&category=<?= $category ?>&page=<?= $i ?>"><?= $i ?></a></li><?php endfor; ?></ul></nav><?php endif; ?></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>

