<?php
require_once __DIR__ . '/../config/app.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('admin.php');
}
verify_csrf();

$mode = $_POST['identity_mode'] ?? 'demo';
$prefix = strtoupper(trim($_POST['account_prefix'] ?? 'CLB'));
if (!in_array($mode, ['demo', 'manual'], true) || !preg_match('/^[A-Z0-9]{2,8}$/', $prefix)) {
    set_flash('error', 'Invalid system settings.');
    redirect('admin.php');
}

$statement = $pdo->prepare(
    'INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?)
     ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
);
$statement->execute(['identity_mode', $mode]);
$statement->execute(['account_prefix', $prefix]);

set_flash('success', 'System settings saved.');
redirect('admin.php');
