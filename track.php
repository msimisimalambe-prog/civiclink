<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/Models/Application.php';

$application = null;
$history = [];
$error = '';
$reference = trim($_GET['reference'] ?? $_POST['reference'] ?? '');

if ($reference !== '') {
    $model = new Application($pdo);
    $application = $model->findByReference($reference);
    if ($application) {
        $history = $model->history((int) $application['id']);
    } else {
        $error = 'No application was found with that reference number.';
    }
}

$pageTitle = 'Track application';
require __DIR__ . '/../app/Views/layout/header.php';
?>
<section class="panel narrow">
    <h1>Track an application</h1>
    <form method="post">
        <label>Reference number<input name="reference" value="<?= e($reference) ?>" required></label>
        <button class="button" type="submit">Track</button>
    </form>
    <?php if ($error): ?><p class="alert error"><?= e($error) ?></p><?php endif; ?>
</section>
<?php if ($application): ?>
<section class="panel">
    <h2><?= e($application['service_type']) ?></h2>
    <p><strong>Reference:</strong> <?= e($application['reference_no']) ?></p>
    <p><strong>Applicant:</strong> <?= e($application['full_name']) ?></p>
    <p><strong>Status:</strong> <span class="status"><?= e($application['status']) ?></span></p>
    <h3>Application history</h3>
    <?php if (!$history): ?><p>No history available.</p><?php else: ?>
        <ol class="timeline">
        <?php foreach ($history as $item): ?>
            <li><strong><?= e($item['new_status']) ?></strong> — <?= e($item['remark']) ?>
                <small><?= e($item['created_at']) ?></small></li>
        <?php endforeach; ?>
        </ol>
    <?php endif; ?>
</section>
<?php endif; ?>
<?php require __DIR__ . '/../app/Views/layout/footer.php'; ?>
