<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
$errors = [];
if (is_post()) {
    verify_csrf();
    $data = [
        'full_name' => trim((string) ($_POST['full_name'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'phone' => trim((string) ($_POST['phone'] ?? '')),
        'subject' => trim((string) ($_POST['subject'] ?? '')),
        'message' => trim((string) ($_POST['message'] ?? '')),
    ];
    if (mb_strlen($data['full_name']) < 2) $errors[] = 'Họ tên phải có ít nhất 2 ký tự.';
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ.';
    if (mb_strlen($data['subject']) < 4) $errors[] = 'Chủ đề phải có ít nhất 4 ký tự.';
    if (mb_strlen($data['message']) < 10) $errors[] = 'Nội dung phải có ít nhất 10 ký tự.';
    if (!$errors) {
        $statement = db()->prepare('INSERT INTO contacts (full_name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)');
        $statement->execute(array_values($data));
        flash('success', 'Mộc Thư đã nhận được lời nhắn của bạn.');
        redirect('contact.php');
    }
}
$pageTitle = 'Liên hệ'; $activePage = 'contact'; require __DIR__ . '/includes/header.php';
?>
<section class="compact-hero"><div class="container"><p class="eyebrow">Kết nối với Mộc Thư</p><h1>Chúng tôi luôn sẵn lòng lắng nghe</h1><p>Gửi câu hỏi, góp ý hoặc đề xuất sách bạn muốn tìm.</p></div></section>
<section class="section-space contact-section"><div class="container contact-grid"><article class="contact-info"><p class="eyebrow">Thông tin liên hệ</p><h2>Ghé thăm không gian đọc</h2><ul><li><strong>Địa chỉ</strong><span>12 Nguyễn Văn Bảo, Gò Vấp, TP.HCM</span></li><li><strong>Điện thoại</strong><span>028 3894 0000</span></li><li><strong>Email</strong><span>hello@mocthu.vn</span></li><li><strong>Giờ mở cửa</strong><span>08:00–21:00, Thứ 2–Chủ nhật</span></li></ul><iframe title="Bản đồ vị trí Mộc Thư" src="https://www.google.com/maps?q=Nguyen+Van+Bao+Go+Vap+Ho+Chi+Minh&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></article><article class="form-panel"><p class="eyebrow">Gửi lời nhắn</p><h2>Chúng tôi có thể giúp gì?</h2><?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?><form method="post" class="needs-validation" novalidate><?= csrf_field() ?><div class="row g-3"><div class="col-md-6"><label class="form-label">Họ và tên</label><input class="form-control" name="full_name" required minlength="2" value="<?= e($_POST['full_name'] ?? '') ?>"><div class="invalid-feedback">Vui lòng nhập họ tên.</div></div><div class="col-md-6"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"><div class="invalid-feedback">Email chưa hợp lệ.</div></div><div class="col-md-6"><label class="form-label">Số điện thoại</label><input class="form-control" name="phone" pattern="[0-9 +]{9,15}" value="<?= e($_POST['phone'] ?? '') ?>"></div><div class="col-md-6"><label class="form-label">Chủ đề</label><input class="form-control" name="subject" required minlength="4" value="<?= e($_POST['subject'] ?? '') ?>"></div><div class="col-12"><label class="form-label">Nội dung</label><textarea class="form-control" name="message" rows="6" required minlength="10"><?= e($_POST['message'] ?? '') ?></textarea></div><div class="col-12"><button class="btn btn-dark btn-lg" type="submit">Gửi lời nhắn</button></div></div></form></article></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
