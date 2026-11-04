<?php
$pageTitle = $pageTitle ?? APP_NAME;
$flash = get_flash();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> | <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<header class="site-header">
    <a class="brand" href="<?= BASE_URL ?>/index.php">CivicLink</a>
    <nav>
        <a href="<?= BASE_URL ?>/index.php">Home</a>
        <a href="<?= BASE_URL ?>/track.php">Track application</a>
        <?php if (current_user()): ?>
            <a href="<?= BASE_URL ?>/dashboard.php">Dashboard</a>
            <?php if (current_user()['role'] === 'admin'): ?>
                <a href="<?= BASE_URL ?>/admin.php">Administration</a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/logout.php">Sign out</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/login.php">Sign in</a>
            <a href="<?= BASE_URL ?>/register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">
<?php if ($flash): ?>
    <div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
<?php endif; ?>
