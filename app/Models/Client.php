<?php

declare(strict_types=1);

namespace App\Models;

final class Client extends BaseModel
{
    public function all(?string $status = null): array
    {
        $sql = "SELECT c.*, cmp.name AS converted_company_name
                FROM clients c
                LEFT JOIN companies cmp ON cmp.id = c.converted_company_id";
        $params = [];

        if ($status !== null && $status !== '') {
            $sql .= " WHERE c.status = :status";
            $params['status'] = $status;
        }

        $sql .= " ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT c.*, cmp.name AS converted_company_name
                FROM clients c
                LEFT JOIN companies cmp ON cmp.id = c.converted_company_id
                WHERE c.id = :id
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $client = $stmt->fetch();

        return $client ?: null;
    }

    public function createFromContact(array $data): int
    {
        $sql = "INSERT INTO clients (name, company_name, email, phone, notes, status, source)
                VALUES (:name, :company_name, :email, :phone, :notes, 'lead', 'landing_contact')";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name'         => $data['name'],
            'company_name' => $data['company_name'],
            'email'        => $data['email'],
            'phone'        => $data['phone'],
            'notes'        => $data['notes'] ?? null,
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO clients (name, company_name, email, phone, rif, phone_secondary, address, notes, status, source)
                VALUES (:name, :company_name, :email, :phone, :rif, :phone_secondary, :address, :notes, :status, :source)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name'            => $data['name'],
            'company_name'    => $data['company_name'],
            'email'           => $data['email'],
            'phone'           => $data['phone'],
            'rif'             => $data['rif'] ?? null,
            'phone_secondary' => $data['phone_secondary'] ?? null,
            'address'         => $data['address'] ?? null,
            'notes'           => $data['notes'] ?? null,
            'status'          => $data['status'] ?? 'lead',
            'source'          => $data['source'] ?? 'admin',
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $sql = "UPDATE clients
                SET name = :name,
                    company_name = :company_name,
                    email = :email,
                    phone = :phone,
                    rif = :rif,
                    phone_secondary = :phone_secondary,
                    address = :address,
                    notes = :notes,
                    status = :status,
                    converted_company_id = :converted_company_id
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id'                   => $id,
            'name'                 => $data['name'],
            'company_name'         => $data['company_name'],
            'email'                => $data['email'],
            'phone'                => $data['phone'],
            'rif'                  => $data['rif'] ?? null,
            'phone_secondary'      => $data['phone_secondary'] ?? null,
            'address'              => $data['address'] ?? null,
            'notes'                => $data['notes'] ?? null,
            'status'               => $data['status'] ?? 'lead',
            'converted_company_id' => $data['converted_company_id'] ?? null,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM clients WHERE id = :id");
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }

    public function countByStatus(): array
    {
        $rows = $this->db->query(
            "SELECT status, COUNT(*) AS total FROM clients GROUP BY status"
        )->fetchAll();

        $counts = [
            'total'     => 0,
            'lead'      => 0,
            'contacted' => 0,
            'qualified' => 0,
            'converted' => 0,
            'discarded' => 0,
        ];

        foreach ($rows as $row) {
            $status = $row['status'];
            $total = (int)$row['total'];
            $counts['total'] += $total;
            if (isset($counts[$status])) {
                $counts[$status] = $total;
            }
        }

        return $counts;
    }
}
