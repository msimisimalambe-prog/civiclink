<?php
declare(strict_types=1);

final class BankService
{
    public function __construct(private PDO $pdo) {}

    public function createDemoAccount(int $userId, string $accountType): string
    {
        $prefix = setting($this->pdo, 'account_prefix', 'CLB');
        $accountNumber = $prefix . '-' . strtoupper(bin2hex(random_bytes(5)));

        $statement = $this->pdo->prepare(
            'INSERT INTO bank_accounts (user_id, account_number, account_type, status)
             VALUES (?, ?, ?, "Active")'
        );
        $statement->execute([$userId, $accountNumber, $accountType]);
        return $accountNumber;
    }

    public function accountsForUser(int $userId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM bank_accounts WHERE user_id = ? ORDER BY created_at DESC'
        );
        $statement->execute([$userId]);
        return $statement->fetchAll();
    }
}
