<?php
declare(strict_types=1);

final class Application
{
    public function __construct(private PDO $pdo) {}

    public function create(int $userId, string $service, array $details): string
    {
        $reference = reference_number('HA');
        $statement = $this->pdo->prepare(
            'INSERT INTO applications (user_id, reference_no, service_type, details_json, status)
             VALUES (?, ?, ?, ?, "Submitted")'
        );
        $statement->execute([
            $userId,
            $reference,
            $service,
            json_encode($details, JSON_THROW_ON_ERROR),
        ]);

        $applicationId = (int) $this->pdo->lastInsertId();
        $this->addHistory($applicationId, null, 'Submitted', 'Application received.');
        return $reference;
    }

    public function addHistory(
        int $applicationId,
        ?string $oldStatus,
        string $newStatus,
        string $remark
    ): void {
        $statement = $this->pdo->prepare(
            'INSERT INTO application_history (application_id, old_status, new_status, remark)
             VALUES (?, ?, ?, ?)'
        );
        $statement->execute([$applicationId, $oldStatus, $newStatus, $remark]);
    }

    public function forUser(int $userId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM applications WHERE user_id = ? ORDER BY created_at DESC'
        );
        $statement->execute([$userId]);
        return $statement->fetchAll();
    }

    public function findByReference(string $reference): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT a.*, u.full_name FROM applications a
             JOIN users u ON u.id = a.user_id WHERE a.reference_no = ? LIMIT 1'
        );
        $statement->execute([$reference]);
        return $statement->fetch() ?: null;
    }

    public function all(): array
    {
        return $this->pdo->query(
            'SELECT a.*, u.full_name, u.email FROM applications a
             JOIN users u ON u.id = a.user_id ORDER BY a.created_at DESC'
        )->fetchAll();
    }

    public function history(int $applicationId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM application_history WHERE application_id = ? ORDER BY created_at DESC'
        );
        $statement->execute([$applicationId]);
        return $statement->fetchAll();
    }

    public function updateStatus(int $id, string $status, string $remark): void
    {
        $this->pdo->beginTransaction();

        try {
            $statement = $this->pdo->prepare('SELECT status FROM applications WHERE id = ? FOR UPDATE');
            $statement->execute([$id]);
            $oldStatus = $statement->fetchColumn();

            if ($oldStatus === false) {
                throw new RuntimeException('Application not found.');
            }

            $update = $this->pdo->prepare('UPDATE applications SET status = ? WHERE id = ?');
            $update->execute([$status, $id]);
            $this->addHistory($id, (string) $oldStatus, $status, $remark);
            $this->pdo->commit();
        } catch (Throwable $exception) {
            $this->pdo->rollBack();
            throw $exception;
        }
    }
}
