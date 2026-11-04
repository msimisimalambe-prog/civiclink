<?php
require_once __DIR__ . '/../config/app.php';

$catalog = require __DIR__ . '/../config/service_catalog.php';
$categoryKey = trim($_GET['category'] ?? '');
$category = $catalog[$categoryKey] ?? null;

if (!$category) {
    http_response_code(404);
    $pageTitle = 'Services not found';
} else {
    $pageTitle = $category['title'];
}

require __DIR__ . '/../app/Views/layout/header.php';
?>
<?php if (!$category): ?>
    <section class="panel">
        <h1>Service category not found</h1>
        <p>Choose one of the service categories from the home page.</p>
        <a class="button" href="<?= BASE_URL ?>/index.php">Back to home</a>
    </section>
<?php else: ?>
    <section class="page-heading">
        <p class="eyebrow">CivicLink services</p>
        <h1><?= e($category['title']) ?></h1>
        <p><?= e($category['intro']) ?></p>
    </section>
    <section class="grid service-list">
        <?php foreach ($category['services'] as $service): ?>
            <article class="panel service-item">
                <h2><?= e($service['title']) ?></h2>
                <p><?= e($service['description']) ?></p>
                <a class="button" href="<?= BASE_URL ?>/<?= e($service['url']) ?>">
                    <?= e($service['action']) ?>
                </a>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>
<?php require __DIR__ . '/../app/Views/layout/footer.php'; ?>
