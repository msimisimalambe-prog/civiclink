<?php
require_once __DIR__ . '/../config/app.php';

$count = (int) $pdo->query('SELECT COUNT(*) FROM users WHERE role = "admin"')->fetchColumn();
if ($count > 0) {
    http_response_code(403);
    exit('An administrator already exists. Delete this setup file.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['full_name'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if ($name && $email && strlen($password) >= 10) {
        $statement = $pdo->prepare(
            'INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, "admin")'
        );
        $statement->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
        echo 'Administrator created. Delete public/admin-setup.php now.';
        exit;
    }
    $error = 'Enter a valid name, email and password of at least 10 characters.';
}
$pageTitle = 'Create administrator';
require __DIR__ . '/../app/Views/layout/header.php';
?>
<section class="panel narrow">
    <h1>Initial administrator setup</h1>
    <?php if (!empty($error)): ?><p class="alert error"><?= e($error) ?></p><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label>Full name<input name="full_name" required></label>
        <label>Email<input type="email" name="email" required></label>
        <label>Password<input type="password" name="password" minlength="10" required></label>
        <button class="button" type="submit">Create administrator</button>
    </form>
</section>
<?php require __DIR__ . '/../app/Views/layout/footer.php'; ?>
