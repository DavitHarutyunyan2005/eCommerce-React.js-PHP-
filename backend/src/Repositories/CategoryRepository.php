<?php

namespace App\Repositories;

class CategoryRepository extends AbstractRepository
{
    protected string $table = 'category';

    //the $id argument is not used in this method, but it is required by the interface
    //to maintain consistency with other repositories. 
    public function findAll(?string $id = null): array
    {
        $query = $this->db->createQueryBuilder()
            ->select('c.id', 'c.name')
            ->from($this->table, 'c');

        return $query->fetchAllAssociative();
    }
}