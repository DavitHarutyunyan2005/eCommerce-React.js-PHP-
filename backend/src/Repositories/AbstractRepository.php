<?php

namespace Repositories;

use Doctrine\DBAL\Connection;

abstract class AbstractRepository implements RepositoryInterface
{
    protected Connection $db;
    protected string $table;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    abstract public function findAll(?string $id = null): array;


    public function findById(int $id): ?array
    {
        return $this->db->createQueryBuilder()
            ->select('*')
            ->from($this->table)
            ->where('id = :id')
            ->setParameter('id', $id)
            ->fetchAssociative();
    }


    public function save(array $data): void
    {
        $this->db->insert($this->table, $data);
    }

    public function delete(int $id): void
    {
        $this->db->delete($this->table, ['id' => $id]);
    }
}
