<?php

namespace App\Repositories;

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

}
