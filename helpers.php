<?php
declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
    exit;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $submitted = $_POST['csrf_token'] ?? '';
    if (!is_string($submitted) || !hash_equals(csrf_token(), $submitted)) {
        http_response_code(419);
        exit('Your form session expired. Go back, refresh the page and try again.');
    }
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_login(): void
{
    if (!current_user()) {
        set_flash('error', 'Please sign in to continue.');
        redirect('login.php');
    }
}

function require_admin(): void
{
    require_login();
    if ((current_user()['role'] ?? '') !== 'admin') {
        http_response_code(403);
        exit('Administrator access is required.');
    }
}

function setting(PDO $pdo, string $key, string $default = ''): string
{
    $statement = $pdo->prepare('SELECT setting_value FROM system_settings WHERE setting_key = ?');
    $statement->execute([$key]);
    $value = $statement->fetchColumn();
    return $value === false ? $default : (string) $value;
}

function reference_number(string $prefix = 'CL'): string
{
    return $prefix . '-' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
}
