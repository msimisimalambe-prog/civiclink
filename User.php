<?php
declare(strict_types=1);

final class User
{
    public function __construct(private PDO $pdo) {}

    public function findByEmail(string $email): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $statement->execute([$email]);
        return $statement->fetch() ?: null;
    }

    public function create(string $name, string $email, string $password): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, "customer")'
        );
        $statement->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
        return (int) $this->pdo->lastInsertId();
    }
}
