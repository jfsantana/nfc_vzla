<?php

declare(strict_types=1);

namespace App\Models;

final class User extends BaseModel
{
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT u.*, r.slug AS role_slug, r.name AS role_name, c.name AS company_name,
                       c.status AS company_status
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
        $sql = "SELECT u.*, r.slug AS role_slug, r.name AS role_name, c.name AS company_name,
                       c.status AS company_status
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

    public function companyAdmins(): array
    {
        return $this->db->query(
            "SELECT u.id, u.company_id, u.name, u.email, u.status, u.created_at,
                    c.name AS company_name
             FROM users u
             INNER JOIN roles r ON r.id = u.role_id AND r.slug = 'company_admin'
             INNER JOIN companies c ON c.id = u.company_id
             ORDER BY c.name, u.name"
        )->fetchAll();
    }

    public function companyAdminById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT u.id, u.company_id, u.name, u.email, u.status, c.name AS company_name
             FROM users u
             INNER JOIN roles r ON r.id = u.role_id AND r.slug = 'company_admin'
             INNER JOIN companies c ON c.id = u.company_id
             WHERE u.id = :id LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function companiesAvailableForAdmin(?int $currentCompanyId = null): array
    {
        $sql = "SELECT c.id, c.name
                FROM companies c
                LEFT JOIN users u ON u.company_id = c.id
                    AND u.role_id = (SELECT id FROM roles WHERE slug = 'company_admin' LIMIT 1)";
        $params = [];

        if ($currentCompanyId === null) {
            $sql .= ' WHERE u.id IS NULL';
        } else {
            $sql .= ' WHERE u.id IS NULL OR c.id = :current_company_id';
            $params['current_company_id'] = $currentCompanyId;
        }

        $stmt = $this->db->prepare($sql . ' ORDER BY c.name');
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function createCompanyAdmin(int $companyId, string $name, string $email, string $password): void
    {
        $this->db->beginTransaction();
        try {
            $company = $this->db->prepare('SELECT id FROM companies WHERE id = :id FOR UPDATE');
            $company->execute(['id' => $companyId]);
            if (!$company->fetchColumn()) {
                throw new \DomainException('La empresa seleccionada no existe.');
            }

            $existing = $this->db->prepare(
                "SELECT 1 FROM users u
                 INNER JOIN roles r ON r.id = u.role_id
                 WHERE u.company_id = :company_id AND r.slug = 'company_admin'
                 LIMIT 1"
            );
            $existing->execute(['company_id' => $companyId]);
            if ($existing->fetchColumn()) {
                throw new \DomainException('La empresa ya tiene un usuario administrador asignado.');
            }

            $roleId = $this->companyAdminRoleId();
            $insert = $this->db->prepare(
                'INSERT INTO users (company_id, role_id, name, email, password, status)
                 VALUES (:company_id, :role_id, :name, :email, :password, :status)'
            );
            $insert->execute([
                'company_id' => $companyId,
                'role_id' => $roleId,
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'status' => 'active',
            ]);
            $this->db->commit();
        } catch (\Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $exception;
        }
    }

    public function updateCompanyAdmin(int $id, string $name, string $email, string $status, ?string $password): void
    {
        $sql = 'UPDATE users SET name = :name, email = :email, status = :status';
        $params = ['id' => $id, 'name' => $name, 'email' => $email, 'status' => $status];

        if ($password !== null) {
            $sql .= ', password = :password';
            $params['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = :id
                  AND role_id = (SELECT id FROM roles WHERE slug = 'company_admin' LIMIT 1)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

    public function deleteCompanyAdmin(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM users
             WHERE id = :id
               AND role_id = (SELECT id FROM roles WHERE slug = 'company_admin' LIMIT 1)"
        );
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }

    public function superadmins(): array
    {
        return $this->db->query(
            "SELECT u.id, u.name, u.email, u.status, u.created_at, u.updated_at,
                    r.name AS role_name, r.slug AS role_slug
             FROM users u
             INNER JOIN roles r ON r.id = u.role_id AND r.slug = 'superadmin'
             ORDER BY u.name ASC"
        )->fetchAll();
    }

    public function superadminById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT u.id, u.name, u.email, u.status, u.created_at, u.updated_at,
                    r.name AS role_name, r.slug AS role_slug
             FROM users u
             INNER JOIN roles r ON r.id = u.role_id AND r.slug = 'superadmin'
             WHERE u.id = :id
             LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function createSuperadmin(string $name, string $email, string $password, string $status = 'active'): int
    {
        $roleId = $this->superadminRoleId();
        $stmt = $this->db->prepare(
            'INSERT INTO users (company_id, role_id, name, email, password, status)
             VALUES (NULL, :role_id, :name, :email, :password, :status)'
        );
        $stmt->execute([
            'role_id'  => $roleId,
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'status'   => $status,
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function updateSuperadmin(int $id, string $name, string $email, string $status, ?string $password = null): void
    {
        $roleId = $this->superadminRoleId();
        $sql = 'UPDATE users SET name = :name, email = :email, status = :status';
        $params = [
            'id'      => $id,
            'role_id' => $roleId,
            'name'    => $name,
            'email'   => $email,
            'status'  => $status,
        ];

        if ($password !== null && $password !== '') {
            $sql .= ', password = :password';
            $params['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql .= ' WHERE id = :id AND role_id = :role_id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

    public function deleteSuperadmin(int $id): bool
    {
        $roleId = $this->superadminRoleId();
        $stmt = $this->db->prepare(
            'DELETE FROM users WHERE id = :id AND role_id = :role_id'
        );
        $stmt->execute([
            'id'      => $id,
            'role_id' => $roleId,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function countSuperadmins(): int
    {
        return (int)$this->db->query(
            "SELECT COUNT(*) FROM users u
             INNER JOIN roles r ON r.id = u.role_id AND r.slug = 'superadmin'"
        )->fetchColumn();
    }

    public function emailExists(string $email, ?int $exceptUserId = null): bool
    {
        $sql = 'SELECT id FROM users WHERE email = :email';
        $params = ['email' => $email];

        if ($exceptUserId !== null) {
            $sql .= ' AND id != :except_id';
            $params['except_id'] = $exceptUserId;
        }

        $stmt = $this->db->prepare($sql . ' LIMIT 1');
        $stmt->execute($params);

        return $stmt->fetchColumn() !== false;
    }

    public function superadminRoleId(): int
    {
        $stmt = $this->db->query("SELECT id FROM roles WHERE slug = 'superadmin' LIMIT 1");
        $roleId = $stmt->fetchColumn();

        if ($roleId === false) {
            throw new \RuntimeException('No se encontró el rol superadmin en la base de datos.');
        }

        return (int)$roleId;
    }

    private function companyAdminRoleId(): int
    {
        $stmt = $this->db->query("SELECT id FROM roles WHERE slug = 'company_admin' LIMIT 1");
        $roleId = $stmt->fetchColumn();

        if ($roleId === false) {
            throw new \RuntimeException('No se encontró el rol company_admin en la base de datos.');
        }

        return (int)$roleId;
    }
}
