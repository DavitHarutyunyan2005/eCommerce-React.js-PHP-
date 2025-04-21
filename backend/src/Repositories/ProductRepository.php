<?php

namespace App\Repositories;

class ProductRepository extends AbstractRepository
{
    protected string $table = 'products';

    public function findAll(?string $category = null, ?string $madeFor = 'All'): array
    {

        // if ($category === null) {
        //     echo "Category is null";
        //     throw new \InvalidArgumentException("Category must be provided.");
        // }
        
        $query = $this->db->createQueryBuilder()
            ->select('p.id', 'p.name', 'p.description', 'p.category', 'p.inStock', 'p.brand', 'p.madeFor')
            ->from($this->table, 'p');
            // ->where('p.category = :category', 'p.madeFor = :madeFor')
            // ->setParameter('category', $category)
            // ->setParameter('madeFor', $madeFor);

        return $query->fetchAllAssociative();
    }

    public function findById(string $id): ?array
    {
        $query = $this->db->createQueryBuilder()
            ->select('p.id', 'p.name', 'p.description', 'p.category', 'p.inStock', 'p.brand', 'p.madeFor')
            ->from($this->table, 'p')
            ->where('p.id = :id')
            ->setParameter('id', $id);

        return $query->fetchAssociative();
    }
}