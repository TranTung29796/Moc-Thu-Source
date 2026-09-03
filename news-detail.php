<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
$statement = db()->prepare("SELECT n.*, p.full_name AS author_name FROM news n JOIN profiles p ON p.user_id = n.user_id WHERE n.id = ? AND n.status = 'published'");
$statement->execute([(int) ($_GET['id'] ?? 0)]);
$item = $statement->fetch();
if (!$item) { http_response_code(404); exit('Không tìm thấy bài viết.'); }
$pageTitle = $item['title']; $activePage = 'news'; require __DIR__ . '/includes/header.php';
?>
<article class="article-page"><header class="container article-header"><p class="eyebrow">Tin Mộc Thư</p><h1><?= e($item['title']) ?></h1><p><?= e($item['excerpt']) ?></p><div><span><?= e($item['author_name']) ?></span><time><?= date('d.m.Y', strtotime($item['published_at'])) ?></time></div></header><div class="article-image"><img src="<?= e($item['image']) ?>" alt="<?= e($item['title']) ?>"></div><div class="container article-content"><p><?= nl2br(e($item['content'])) ?></p><h2>Đọc mỗi ngày, thay đổi từng ngày</h2><p>Một thói quen nhỏ sẽ trở nên bền vững khi được gắn với niềm vui. Hãy bắt đầu bằng cuốn sách bạn thật sự tò mò.</p></div></article>
<?php require __DIR__ . '/includes/footer.php'; ?>

