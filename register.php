<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
if (current_user()) redirect('index.php');
$errors = [];
if (is_post()) {
    verify_csrf();
    $fullName = trim((string) ($_POST['full_name'] ?? ''));
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirmation = (string) ($_POST['password_confirmation'] ?? '');
    if (mb_strlen($fullName) < 2) $errors[] = 'Họ tên phải có ít nhất 2 ký tự.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ.';
    if (strlen($password) < 8) $errors[] = 'Mật khẩu phải có ít nhất 8 ký tự.';
    if ($password !== $confirmation) $errors[] = 'Mật khẩu xác nhận không khớp.';
    $check = db()->prepare('SELECT id FROM users WHERE email = ?'); $check->execute([$email]);
    if ($check->fetch()) $errors[] = 'Email này đã được đăng ký.';
    if (!$errors) {
        db()->beginTransaction();
        try {
            $user = db()->prepare("INSERT INTO users (email, password_hash, role) VALUES (?, ?, 'customer')");
            $user->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
            $profile = db()->prepare('INSERT INTO profiles (user_id, full_name, phone) VALUES (?, ?, ?)');
            $profile->execute([(int) db()->lastInsertId(), $fullName, $phone]);
            db()->commit();
            flash('success', 'Đăng ký thành công. Bạn có thể đăng nhập ngay.');
            redirect('login.php');
        } catch (Throwable $error) {
            db()->rollBack();
            $errors[] = 'Không thể tạo tài khoản lúc này.';
        }
    }
}
$pageTitle = 'Đăng ký'; $bodyClass = 'auth-layout'; require __DIR__ . '/includes/header.php';
?>
<section class="auth-page"><div class="auth-visual"><img src="<?= url('assets/cover-children.png') ?>" alt="Minh họa sách Mộc Thư"></div><div class="auth-form-wrap"><article class="auth-card"><p class="eyebrow">Thành viên mới</p><h1>Tạo tài khoản</h1><p>Lưu thông tin và nhận gợi ý sách phù hợp với bạn.</p><?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?><form method="post" class="needs-validation" novalidate><?= csrf_field() ?><label class="form-label">Họ và tên<input class="form-control" name="full_name" required minlength="2" value="<?= e($_POST['full_name'] ?? '') ?>"></label><label class="form-label">Email<input class="form-control" type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"></label><label class="form-label">Số điện thoại<input class="form-control" name="phone" pattern="[0-9 +]{9,15}" value="<?= e($_POST['phone'] ?? '') ?>"></label><label class="form-label">Mật khẩu<input class="form-control" type="password" name="password" required minlength="8"></label><label class="form-label">Xác nhận mật khẩu<input class="form-control" type="password" name="password_confirmation" required minlength="8"></label><button class="btn btn-dark btn-lg w-100" type="submit">Đăng ký</button></form><p class="auth-switch">Đã có tài khoản? <a href="<?= url('login.php') ?>">Đăng nhập</a></p></article></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
