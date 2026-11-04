<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        (new AuthController($pdo))->register($_POST);
        set_flash('success', 'Registration successful. You can now sign in.');
        redirect('login.php');
    } catch (Throwable $exception) {
        set_flash('error', $exception->getMessage());
    }
}
$pageTitle = 'Register';
require __DIR__ . '/../app/Views/layout/header.php';
?>
<section class="panel narrow">
    <h1>Create your account</h1>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label>Full name<input name="full_name" required maxlength="120"></label>
        <label>Email address<input type="email" name="email" required maxlength="190"></label>
        <label>Password<input type="password" name="password" minlength="8" required></label>
        <button class="button" type="submit">Register</button>
    </form>
</section>
<?php require __DIR__ . '/../app/Views/layout/footer.php'; ?>
