<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
if (current_user()) redirect(is_admin() ? 'admin/index.php' : 'index.php');
$error = '';
$rememberedEmail = (string) ($_COOKIE['remember_email'] ?? '');
if (is_post()) {
    verify_csrf();
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    $statement = db()->prepare("SELECT u.id, u.email, u.password_hash, u.role, u.status, p.full_name FROM users u JOIN profiles p ON p.user_id = u.id WHERE u.email = ? LIMIT 1");
    $statement->execute([$email]);
    $user = $statement->fetch();
    if ($user && $user['status'] === 'active' && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => (int) $user['id'], 'email' => $user['email'], 'role' => $user['role'], 'full_name' => $user['full_name']];
        if (!empty($_POST['remember'])) setcookie('remember_email', $email, ['expires' => time() + 2592000, 'path' => '/', 'samesite' => 'Lax']);
        else setcookie('remember_email', '', ['expires' => time() - 3600, 'path' => '/']);
        flash('success', 'Đăng nhập thành công.');
        redirect($user['role'] === 'admin' ? 'admin/index.php' : 'index.php');
    }
    $error = 'Email hoặc mật khẩu chưa chính xác.';
}
$pageTitle = 'Đăng nhập'; $bodyClass = 'auth-layout'; require __DIR__ . '/includes/header.php';
?>
<section class="auth-page"><div class="auth-visual auth-visual-login"><img src="<?= url('assets/cover-literary.png') ?>" alt="Minh họa sách Mộc Thư"></div><div class="auth-form-wrap"><article class="auth-card"><p class="eyebrow">Chào mừng trở lại</p><h1>Đăng nhập</h1><p>Tiếp tục hành trình đọc sách cùng Mộc Thư.</p><?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?><form method="post" class="needs-validation" novalidate><?= csrf_field() ?><label class="form-label">Email<input class="form-control" type="email" name="email" required value="<?= e($_POST['email'] ?? $rememberedEmail) ?>"></label><label class="form-label">Mật khẩu<input class="form-control" type="password" name="password" required minlength="8"></label><label class="form-check"><input class="form-check-input" type="checkbox" name="remember" value="1" <?= $rememberedEmail ? 'checked' : '' ?>><span class="form-check-label">Ghi nhớ email trên thiết bị này</span></label><button class="btn btn-dark btn-lg w-100" type="submit">Đăng nhập</button></form><div class="demo-account"><strong>Tài khoản quản trị mẫu</strong><span>admin@mocthu.vn / password</span></div><p class="auth-switch">Chưa có tài khoản? <a href="<?= url('register.php') ?>">Đăng ký</a></p></article></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
