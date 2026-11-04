<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/Controllers/ApplicationController.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        $reference = (new ApplicationController($pdo))->submit(current_user()['id'], $_POST);
        set_flash('success', 'Application submitted. Your reference is ' . $reference);
        redirect('dashboard.php');
    } catch (Throwable $exception) {
        set_flash('error', $exception->getMessage());
    }
}

$services = [
    'Passport application',
    'Passport renewal',
    'Passport replacement',
    'Birth certificate',
    'Marriage certificate',
    'Death certificate',
    'National ID replacement',
];
$selectedService = trim($_GET['service'] ?? $_POST['service_type'] ?? '');
if (!in_array($selectedService, $services, true)) {
    $selectedService = '';
}
$pageTitle = 'Apply for a service';
require __DIR__ . '/../app/Views/layout/header.php';
?>
<section class="panel">
    <h1>Home Affairs application</h1>
    <p>Complete the details below. Submit only information relevant to the selected service.</p>
    <form method="post" class="form-grid">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label>Service
            <select name="service_type" required>
                <option value="">Select a service</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?= e($service) ?>" <?= $selectedService === $service ? 'selected' : '' ?>><?= e($service) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Applicant full name<input name="applicant_name" required maxlength="120"></label>
        <label>National ID number<input name="national_id" maxlength="40"></label>
        <label>Phone number<input name="phone" required maxlength="30"></label>
        <label>Email address<input type="email" name="email" maxlength="190"></label>
        <label>Date of birth<input type="date" name="date_of_birth"></label>
        <label class="full">Additional information<textarea name="notes" rows="4" maxlength="1500"></textarea></label>
        <div class="full"><button class="button" type="submit">Submit application</button></div>
    </form>
</section>
<?php require __DIR__ . '/../app/Views/layout/footer.php'; ?>
