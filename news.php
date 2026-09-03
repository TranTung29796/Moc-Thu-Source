<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
$items = db()->query("SELECT n.*, p.full_name AS author_name FROM news n JOIN users u ON u.id = n.user_id JOIN profiles p ON p.user_id = u.id WHERE n.status = 'published' ORDER BY n.published_at DESC")->fetchAll();
$pageTitle = 'Tin tức'; $activePage = 'news';
require __DIR__ . '/includes/header.php';
?>
<section class="compact-hero"><div class="container"><p class="eyebrow">Góc đọc Mộc Thư</p><h1>Tin tức & cảm hứng đọc</h1><p>Câu chuyện sách, tác giả và các hoạt động dành cho cộng đồng.</p></div></section>
<section class="section-space"><div class="container"><div class="row g-4"><?php foreach ($items as $item): ?><div class="col-12 col-md-6 col-lg-4"><article class="news-card h-100"><a href="<?= url('news-detail.php?id=' . (int) $item['id']) ?>"><img src="<?= e($item['image']) ?>" alt="<?= e($item['title']) ?>"></a><div><time><?= date('d.m.Y', strtotime($item['published_at'])) ?></time><h2><a href="<?= url('news-detail.php?id=' . (int) $item['id']) ?>"><?= e($item['title']) ?></a></h2><p><?= e($item['excerpt']) ?></p><a class="text-link" href="<?= url('news-detail.php?id=' . (int) $item['id']) ?>">Đọc bài viết →</a></div></article></div><?php endforeach; ?></div></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>

