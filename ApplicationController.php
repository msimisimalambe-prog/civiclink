<?php
declare(strict_types=1);

require_once __DIR__ . '/../Models/Application.php';

final class ApplicationController
{
    private const SERVICES = [
        'Passport application',
        'Passport renewal',
        'Passport replacement',
        'Birth certificate',
        'Marriage certificate',
        'Death certificate',
        'National ID replacement',
    ];

    public function __construct(private PDO $pdo) {}

    public function submit(int $userId, array $input): string
    {
        $service = trim($input['service_type'] ?? '');
        if (!in_array($service, self::SERVICES, true)) {
            throw new InvalidArgumentException('Choose a valid Home Affairs service.');
        }

        $details = [
            'applicant_name' => trim($input['applicant_name'] ?? ''),
            'national_id' => trim($input['national_id'] ?? ''),
            'phone' => trim($input['phone'] ?? ''),
            'email' => trim($input['email'] ?? ''),
            'date_of_birth' => trim($input['date_of_birth'] ?? ''),
            'notes' => trim($input['notes'] ?? ''),
        ];

        if ($details['applicant_name'] === '' || $details['phone'] === '') {
            throw new InvalidArgumentException('Applicant name and phone number are required.');
        }

        return (new Application($this->pdo))->create($userId, $service, $details);
    }
}
