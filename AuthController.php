<?php
declare(strict_types=1);

require_once __DIR__ . '/../Models/User.php';

final class AuthController
{
    public function __construct(private PDO $pdo) {}

    public function register(array $input): void
    {
        $name = trim($input['full_name'] ?? '');
        $email = filter_var(trim($input['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $password = $input['password'] ?? '';

        if ($name === '' || !$email || strlen($password) < 8) {
            throw new InvalidArgumentException('Enter your name, a valid email and a password of at least 8 characters.');
        }

        $users = new User($this->pdo);
        if ($users->findByEmail($email)) {
            throw new InvalidArgumentException('That email address is already registered.');
        }

        $users->create($name, $email, $password);
    }

    public function login(string $email, string $password): bool
    {
        $user = (new User($this->pdo))->findByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => $user['full_name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
        return true;
    }
}
