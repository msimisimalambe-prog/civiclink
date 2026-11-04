<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/Services/IdentityService.php';
require_once __DIR__ . '/../app/Services/BankService.php';
require_login();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        if (($_POST['action'] ?? '') === 'identity') {
            $nationalId = trim($_POST['national_id'] ?? '');
            if ($nationalId === '') {
                throw new InvalidArgumentException('Enter your national ID number.');
            }
            $message = 'Identity check status: ' .
                (new IdentityService($pdo))->verify(current_user()['id'], $nationalId);
        } elseif (($_POST['action'] ?? '') === 'account') {
            $type = trim($_POST['account_type'] ?? 'Savings');
            if (!in_array($type, ['Savings', 'Current'], true)) {
                throw new InvalidArgumentException('Choose a valid account type.');
            }
            $number = (new BankService($pdo))->createDemoAccount(current_user()['id'], $type);
            $message = 'Demonstration account created: ' . $number;
        }
    } catch (Throwable $exception) {
        $message = $exception->getMessage();
    }
}
$pageTitle = 'Identity and banking';
require __DIR__ . '/../app/Views/layout/header.php';
?>
<section class="grid">
    <article class="panel" id="identity">
        <h1>Identity verification</h1>
        <p>Verification mode: <?= e(setting($pdo, 'identity_mode', 'demo')) ?></p>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="action" value="identity">
            <label>National ID number<input name="national_id" required></label>
            <button class="button" type="submit">Submit identity check</button>
        </form>
    </article>
    <article class="panel" id="banking">
        <h1>Bank account</h1>
        <p>Create a local demonstration account record.</p>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="action" value="account">
            <label>Account type
                <select name="account_type">
                    <option>Savings</option>
                    <option>Current</option>
                </select>
            </label>
            <button class="button" type="submit">Create account</button>
        </form>
    </article>
</section>
<?php if ($message): ?><p class="alert success"><?= e($message) ?></p><?php endif; ?>
<?php require __DIR__ . '/../app/Views/layout/footer.php'; ?>
