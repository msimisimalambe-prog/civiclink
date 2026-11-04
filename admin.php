<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/Models/Application.php';
require_admin();

$model = new Application($pdo);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        $status = trim($_POST['status'] ?? '');
        $allowed = ['Submitted', 'Under review', 'Additional information required', 'Approved', 'Rejected', 'Completed'];
        if (!in_array($status, $allowed, true)) {
            throw new InvalidArgumentException('Choose a valid application status.');
        }
        $model->updateStatus((int) $_POST['application_id'], $status, trim($_POST['remark'] ?? 'Status updated.'));
        set_flash('success', 'Application status updated.');
    } catch (Throwable $exception) {
        set_flash('error', $exception->getMessage());
    }
    redirect('admin.php');
}
$applications = $model->all();
$pageTitle = 'Administration';
require __DIR__ . '/../app/Views/layout/header.php';
?>
<h1>Application administration</h1>
<section class="panel">
    <div class="table-wrap"><table>
        <thead><tr><th>Reference / Applicant</th><th>Service</th><th>Status</th><th>Update</th></tr></thead>
        <tbody>
        <?php foreach ($applications as $application): ?>
            <tr>
                <td><?= e($application['reference_no']) ?><br><small><?= e($application['full_name']) ?> · <?= e($application['email']) ?></small></td>
                <td><?= e($application['service_type']) ?></td>
                <td><?= e($application['status']) ?></td>
                <td>
                    <form method="post" class="inline-form">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="application_id" value="<?= (int) $application['id'] ?>">
                        <select name="status">
                            <?php foreach (['Submitted', 'Under review', 'Additional information required', 'Approved', 'Rejected', 'Completed'] as $status): ?>
                                <option value="<?= e($status) ?>" <?= $status === $application['status'] ? 'selected' : '' ?>><?= e($status) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input name="remark" placeholder="Add a remark" maxlength="500" required>
                        <button class="button small" type="submit">Save</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
</section>
<section class="panel">
    <h2>System settings</h2>
    <form method="post" action="<?= BASE_URL ?>/settings.php" class="form-grid">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label>Identity mode
            <select name="identity_mode">
                <option value="demo" <?= setting($pdo, 'identity_mode', 'demo') === 'demo' ? 'selected' : '' ?>>Automatic demo</option>
                <option value="manual" <?= setting($pdo, 'identity_mode', 'demo') === 'manual' ? 'selected' : '' ?>>Manual review</option>
            </select>
        </label>
        <label>Bank account prefix<input name="account_prefix" value="<?= e(setting($pdo, 'account_prefix', 'CLB')) ?>" maxlength="8"></label>
        <div class="full"><button class="button" type="submit">Save settings</button></div>
    </form>
</section>
<?php require __DIR__ . '/../app/Views/layout/footer.php'; ?>
