<?php
require_once __DIR__ . '/../config/app.php';

$serviceCatalog = require __DIR__ . '/../config/service_catalog.php';
$pageTitle = 'Home';
require __DIR__ . '/../app/Views/layout/header.php';
?>
<section class="hero">
    <p class="eyebrow">Banking and Home Affairs services</p>
    <h1>Services in one place.</h1>
    <p>Submit selected Home Affairs applications, manage your profile and follow application progress.</p>
    <div class="actions">
        <a class="button" href="<?= BASE_URL ?>/register.php">Create an account</a>
        <a class="button secondary" href="<?= BASE_URL ?>/track.php">Track an application</a>
    </div>
</section>

<section class="grid service-categories" aria-label="Service categories">
    <?php foreach ($serviceCatalog as $key => $category): ?>
        <a class="panel service-card" href="<?= BASE_URL ?>/services.php?category=<?= urlencode($key) ?>">
            <h2><?= e($category['title']) ?></h2>
            <p><?= e($category['description']) ?></p>
            <span class="card-link">Explore services <span aria-hidden="true">→</span></span>
        </a>
    <?php endforeach; ?>
</section>
<?php require __DIR__ . '/../app/Views/layout/footer.php'; ?>
