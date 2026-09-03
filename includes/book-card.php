<article class="book-card h-100">
  <a class="book-cover" href="<?= url('book-detail.php?id=' . (int) $book['id']) ?>">
    <img src="<?= e($book['cover_image']) ?>" alt="Bìa sách <?= e($book['title']) ?>" loading="lazy">
    <?php if (!empty($book['sale_price'])): ?><span class="sale-badge">Giảm giá</span><?php endif; ?>
  </a>
  <div class="book-card-body">
    <span class="book-category"><?= e($book['category_name'] ?? '') ?></span>
    <h3><a href="<?= url('book-detail.php?id=' . (int) $book['id']) ?>"><?= e($book['title']) ?></a></h3>
    <p><?= e($book['short_description']) ?></p>
    <div class="book-card-footer">
      <div class="book-price"><strong><?= money($book['sale_price'] ?: $book['price']) ?></strong><?php if (!empty($book['sale_price'])): ?><del><?= money($book['price']) ?></del><?php endif; ?></div>
      <a class="round-link" href="<?= url('book-detail.php?id=' . (int) $book['id']) ?>" aria-label="Xem <?= e($book['title']) ?>">→</a>
    </div>
  </div>
</article>

