<?php

namespace App\Repositories;

class AttributeRepository extends AbstractRepository
{
    protected string $table = 'attributes';

    public  function findAll(?string $productId = null): array
    {
        if ($productId === null) {
            throw new \InvalidArgumentException("Product ID must be provided.");
        }

        try {
            $query = $this->db->createQueryBuilder()
                ->select('a.id, a.name, a.type')
                ->from($this->table, 'a')
                ->where('a.product_id = :productId')
                ->setParameter('productId', $productId);

            $result = $query->fetchAllAssociative();

            return $result ?: [];
        } catch (\Exception $e) {
            throw new \RuntimeException("Error fetching attributes: " . $e->getMessage(), 0, $e);
        }
    }
}