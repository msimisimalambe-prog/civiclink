<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/Models/Application.php';
require_once __DIR__ . '/../app/Services/BankService.php';
require_login();

$user = current_user();
$applications = (new Application($pdo))->forUser($user['id']);
$accounts = (new BankService($pdo))->accountsForUser($user['id']);
$pageTitle = 'Dashboard';
require __DIR__ . '/../app/Views/layout/header.php';
?>
<h1>Welcome, <?= e($user['name']) ?></h1>
<div class="actions">
    <a class="button" href="<?= BASE_URL ?>/apply.php">New Home Affairs application</a>
    <a class="button secondary" href="<?= BASE_URL ?>/profile.php">Identity and bank services</a>
</div>
<section class="panel">
    <h2>Your applications</h2>
    <?php if (!$applications): ?><p>You have not submitted an application yet.</p><?php else: ?>
    <div class="table-wrap"><table>
        <thead><tr><th>Reference</th><th>Service</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
        <?php foreach ($applications as $application): ?>
            <tr>
                <td><a href="<?= BASE_URL ?>/track.php?reference=<?= urlencode($application['reference_no']) ?>"><?= e($application['reference_no']) ?></a></td>
                <td><?= e($application['service_type']) ?></td>
                <td><span class="status"><?= e($application['status']) ?></span></td>
                <td><?= e($application['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <?php endif; ?>
</section>
<section class="panel" id="accounts">
    <h2>Demonstration bank accounts</h2>
    <?php if (!$accounts): ?><p>No demonstration accounts created.</p><?php else: ?>
    <ul><?php foreach ($accounts as $account): ?>
        <li><?= e($account['account_number']) ?> — <?= e($account['account_type']) ?> (<?= e($account['status']) ?>)</li>
    <?php endforeach; ?></ul>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../app/Views/layout/footer.php'; ?>
