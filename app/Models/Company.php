<?php

declare(strict_types=1);

namespace App\Models;

final class Company extends BaseModel
{
    public function all(): array
    {
        return $this->db->query(
            "SELECT c.id, c.name, c.slug, c.status, c.subscription_ends_at,
                    p.name AS plan_name, COUNT(DISTINCT u.id) AS admin_count,
                    COUNT(DISTINCT cards.id) AS card_count
             FROM companies c
             LEFT JOIN plans p ON p.id = c.plan_id
             LEFT JOIN users u ON u.company_id = c.id AND u.role_id = (
                 SELECT id FROM roles WHERE slug = 'company_admin' LIMIT 1
             )
             LEFT JOIN cards ON cards.company_id = c.id
             GROUP BY c.id, p.name
             ORDER BY c.created_at DESC, c.id DESC"
        )->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM companies WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $company = $stmt->fetch();

        return $company ?: null;
    }

    public function plans(): array
    {
        return $this->db->query(
            "SELECT id, name FROM plans WHERE status = 'active' ORDER BY name"
        )->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO companies (plan_id, name, slug, subscription_ends_at, status)
             VALUES (:plan_id, :name, :slug, :subscription_ends_at, :status)'
        );
        $stmt->execute([
            'plan_id' => $data['plan_id'],
            'name' => $data['name'],
            'slug' => $data['slug'],
            'subscription_ends_at' => $data['subscription_ends_at'],
            'status' => $data['status'],
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE companies
             SET plan_id = :plan_id, name = :name, slug = :slug,
                 subscription_ends_at = :subscription_ends_at, status = :status
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'plan_id' => $data['plan_id'],
            'name' => $data['name'],
            'slug' => $data['slug'],
            'subscription_ends_at' => $data['subscription_ends_at'],
            'status' => $data['status'],
        ]);
    }

    public function delete(int $id): bool
    {
        $this->db->beginTransaction();
        try {
            $company = $this->db->prepare('SELECT id FROM companies WHERE id = :id FOR UPDATE');
            $company->execute(['id' => $id]);
            if (!$company->fetchColumn()) {
                $this->db->commit();
                return false;
            }

            $cards = $this->db->prepare('SELECT COUNT(*) FROM cards WHERE company_id = :id');
            $cards->execute(['id' => $id]);
            if ((int)$cards->fetchColumn() > 0) {
                $this->db->commit();
                return false;
            }

            $delete = $this->db->prepare('DELETE FROM companies WHERE id = :id');
            $delete->execute(['id' => $id]);
            $deleted = $delete->rowCount() > 0;
            $this->db->commit();

            return $deleted;
        } catch (\Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $exception;
        }
    }

    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        $sql = 'SELECT 1 FROM companies WHERE slug = :slug';
        $params = ['slug' => $slug];

        if ($exceptId !== null) {
            $sql .= ' AND id <> :id';
            $params['id'] = $exceptId;
        }

        $stmt = $this->db->prepare($sql . ' LIMIT 1');
        $stmt->execute($params);

        return $stmt->fetchColumn() !== false;
    }

    public function planExists(int $planId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT 1 FROM plans WHERE id = :id AND status = 'active' LIMIT 1"
        );
        $stmt->execute(['id' => $planId]);

        return $stmt->fetchColumn() !== false;
    }

    public function portalSummary(int $companyId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT c.id, c.name, c.status, c.subscription_ends_at, p.name AS plan_name,
                    (SELECT COUNT(*) FROM cards WHERE company_id = c.id) AS card_count,
                    (SELECT COUNT(*) FROM cards WHERE company_id = c.id AND status = 'active') AS active_card_count,
                    (SELECT COUNT(*) FROM analytics a
                     INNER JOIN cards ca ON ca.id = a.card_id
                     WHERE ca.company_id = c.id) AS scan_count
             FROM companies c
             LEFT JOIN plans p ON p.id = c.plan_id
             WHERE c.id = :company_id LIMIT 1"
        );
        $stmt->execute(['company_id' => $companyId]);
        $company = $stmt->fetch();

        return $company ?: null;
    }
}
