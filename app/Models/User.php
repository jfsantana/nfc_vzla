<?php

declare(strict_types=1);

namespace App\Models;

final class User extends BaseModel
{
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT u.*, r.slug AS role_slug, r.name AS role_name, c.name AS company_name
                FROM users u
                INNER JOIN roles r ON u.role_id = r.id
                LEFT JOIN companies c ON u.company_id = c.id
                WHERE u.email = :email
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT u.*, r.slug AS role_slug, r.name AS role_name, c.name AS company_name
                FROM users u
                INNER JOIN roles r ON u.role_id = r.id
                LEFT JOIN companies c ON u.company_id = c.id
                WHERE u.id = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function getMetrics(): array
    {
        $totalCompanies = (int)$this->db->query("SELECT COUNT(*) FROM companies")->fetchColumn();
        $totalCards = (int)$this->db->query("SELECT COUNT(*) FROM cards")->fetchColumn();
        $totalUsers = (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalScans = (int)$this->db->query("SELECT COUNT(*) FROM analytics")->fetchColumn();

        return [
            'total_companies' => $totalCompanies,
            'total_cards'     => $totalCards,
            'total_users'     => $totalUsers,
            'total_scans'     => $totalScans,
        ];
    }
}
