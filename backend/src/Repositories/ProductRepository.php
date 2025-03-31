<?php

namespace Repositories;

class ProductRepository extends AbstractRepository
{
    protected string $table = 'products';

    public function findAll(?string $categoryId = null): array
    {
        if ($categoryId === null) {
            throw new \InvalidArgumentException("Category ID must be provided.");
        }
        
        $query = $this->db->createQueryBuilder()
            ->select('p.id', 'p.name', 'p.description', 'p.category', 'p.inStock', 'p.brand')
            ->from($this->table, 'p')
            ->where('p.category_id = :categoryId')
            ->setParameter('categoryId', $categoryId);

        return $query->fetchAllAssociative();
    }
}
