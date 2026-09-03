</main>
<footer class="site-footer">
  <div class="container footer-grid">
    <div><a class="navbar-brand footer-brand" href="<?= url('index.php') ?>"><span class="brand-mark">M</span><span>Mộc <strong>Thư</strong></span></a><p>Gìn giữ niềm vui đọc sách và đưa tri thức đến gần hơn với mọi gia đình Việt.</p></div>
    <div><h2>Khám phá</h2><ul><li><a href="<?= url('books.php') ?>">Danh mục sách</a></li><li><a href="<?= url('news.php') ?>">Tin tức</a></li><li><a href="<?= url('about.php') ?>">Về chúng tôi</a></li></ul></div>
    <div><h2>Hỗ trợ</h2><ul><li><a href="<?= url('contact.php') ?>">Liên hệ</a></li><li><a href="<?= url('login.php') ?>">Tài khoản</a></li><li><a href="<?= url('admin/index.php') ?>">Quản trị</a></li></ul></div>
    <div><h2>Thông tin</h2><p>12 Nguyễn Văn Bảo, Gò Vấp, TP.HCM</p><p>028 3894 0000</p><p>hello@mocthu.vn</p></div>
  </div>
  <div class="container footer-bottom">© <?= date('Y') ?> Mộc Thư. Đồ án Website sách PHP & MySQL.</div>
</footer>
<script src="<?= url('assets/vendor/bootstrap/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= url('assets/js/app.js') ?>?v=<?= filemtime(__DIR__ . '/../public/assets/js/app.js') ?>"></script>
</body>
</html>
