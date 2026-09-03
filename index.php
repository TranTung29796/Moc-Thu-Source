<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

$featuredBooks = db()->query("SELECT b.*, c.name AS category_name FROM books b JOIN categories c ON c.id = b.category_id WHERE b.status = 'published' AND b.featured = 1 ORDER BY b.created_at DESC LIMIT 4")->fetchAll();
$latestNews = db()->query("SELECT * FROM news WHERE status = 'published' ORDER BY published_at DESC LIMIT 3")->fetchAll();
$pageTitle = 'Nhà sách trực tuyến';
$activePage = 'home';
require __DIR__ . '/includes/header.php';
?>
<section class="hero-section">
  <div id="homeSlider" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5500">
    <div class="carousel-indicators"><button type="button" data-bs-target="#homeSlider" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button><button type="button" data-bs-target="#homeSlider" data-bs-slide-to="1" aria-label="Slide 2"></button></div>
    <div class="carousel-inner">
      <div class="carousel-item active"><div class="hero-media hero-media-main"></div><div class="container hero-content"><p class="eyebrow">Nhà sách của những tâm hồn rộng mở</p><h1>Mỗi trang sách,<br>một thế giới mới.</h1><p>Khám phá những tựa sách được chọn lọc kỹ lưỡng, từ văn học đến khoa học và kỹ năng sống.</p><div class="hero-actions"><a class="btn btn-dark btn-lg" href="<?= url('books.php') ?>">Khám phá sách <span>→</span></a><a class="text-link" href="<?= url('about.php') ?>">Câu chuyện Mộc Thư</a></div></div></div>
      <div class="carousel-item"><div class="hero-media hero-media-second"></div><div class="container hero-content"><p class="eyebrow">Tủ sách mới tháng 8</p><h2>Đọc sâu hơn.<br>Sống rộng hơn.</h2><p>Ưu đãi đến 20% cho các tựa sách khoa học và phát triển bản thân nổi bật.</p><div class="hero-actions"><a class="btn btn-dark btn-lg" href="<?= url('books.php?category=2') ?>">Xem tủ sách khoa học <span>→</span></a></div></div></div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#homeSlider" data-bs-slide="prev" aria-label="Slide trước"><span class="carousel-control-prev-icon"></span></button><button class="carousel-control-next" type="button" data-bs-target="#homeSlider" data-bs-slide="next" aria-label="Slide sau"><span class="carousel-control-next-icon"></span></button>
  </div>
</section>

<section class="benefit-strip" aria-label="Quyền lợi khách hàng"><div class="container benefit-grid"><article><span>01</span><div><strong>Chọn lọc kỹ lưỡng</strong><p>Mỗi cuốn sách đều được đội ngũ biên tập đọc và đánh giá.</p></div></article><article><span>02</span><div><strong>Giao hàng toàn quốc</strong><p>Đóng gói chắc chắn, theo dõi hành trình đơn giản.</p></div></article><article><span>03</span><div><strong>Đồng hành cùng bạn đọc</strong><p>Gợi ý sách phù hợp với sở thích và mục tiêu đọc.</p></div></article></div></section>

<section class="section-space"><div class="container"><div class="section-heading"><div><p class="eyebrow">Được yêu thích</p><h2>Sách nổi bật</h2></div><a class="text-link" href="<?= url('books.php') ?>">Xem tất cả →</a></div><div class="row g-4"><?php foreach ($featuredBooks as $book): ?><div class="col-12 col-sm-6 col-lg-3"><?php require __DIR__ . '/includes/book-card.php'; ?></div><?php endforeach; ?></div></div></section>

<section class="quote-band"><div class="container"><blockquote>“Một cuốn sách hay cho ta một điều để suy nghĩ, một điều để cảm nhận và một điều để mang theo.”</blockquote><p>— Tinh thần tuyển chọn của Mộc Thư</p></div></section>

<section class="section-space"><div class="container"><div class="section-heading"><div><p class="eyebrow">Góc đọc</p><h2>Tin tức & cảm hứng</h2></div><a class="text-link" href="<?= url('news.php') ?>">Đọc thêm →</a></div><div class="row g-4"><?php foreach ($latestNews as $item): ?><div class="col-12 col-lg-4"><article class="news-card"><a href="<?= url('news-detail.php?id=' . (int) $item['id']) ?>"><img src="<?= e($item['image']) ?>" alt="<?= e($item['title']) ?>" loading="lazy"></a><div><time datetime="<?= e($item['published_at']) ?>"><?= date('d.m.Y', strtotime($item['published_at'])) ?></time><h3><a href="<?= url('news-detail.php?id=' . (int) $item['id']) ?>"><?= e($item['title']) ?></a></h3><p><?= e($item['excerpt']) ?></p></div></article></div><?php endforeach; ?></div></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
