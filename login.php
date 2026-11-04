<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $success = (new AuthController($pdo))->login(trim($_POST['email'] ?? ''), $_POST['password'] ?? '');
    if ($success) {
        redirect('dashboard.php');
    }
    set_flash('error', 'The email or password is incorrect.');
}
$pageTitle = 'Sign in';
require __DIR__ . '/../app/Views/layout/header.php';
?>
<section class="panel narrow">
    <h1>Sign in</h1>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label>Email address<input type="email" name="email" required></label>
        <label>Password<input type="password" name="password" required></label>
        <button class="button" type="submit">Sign in</button>
    </form>
</section>
<?php require __DIR__ . '/../app/Views/layout/footer.php'; ?>
