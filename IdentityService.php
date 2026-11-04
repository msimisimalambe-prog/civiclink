<?php
declare(strict_types=1);

final class IdentityService
{
    public function __construct(private PDO $pdo) {}

    public function verify(int $userId, string $nationalId): string
    {
        $mode = setting($this->pdo, 'identity_mode', 'demo');
        $status = $mode === 'manual' ? 'Pending review' : 'Demo verified';

        $statement = $this->pdo->prepare(
            'INSERT INTO identity_checks (user_id, national_id, status)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE national_id = VALUES(national_id),
             status = VALUES(status), checked_at = CURRENT_TIMESTAMP'
        );
        $statement->execute([$userId, $nationalId, $status]);
        return $status;
    }
}
