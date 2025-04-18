<?php

namespace App\Repositories;


class PricesRepository extends AbstractRepository
{
    protected string $table = 'prices';

    public function findAll(?string $productId = null): array
    {
        if ($productId === null) {
            throw new \InvalidArgumentException("Product ID must be provided.");
        }
        
        $query = $this->db->createQueryBuilder()
            ->select('p.amount, p.currency_label, p.currency_symbol')
            ->from($this->table, 'p')
            ->where('p.product_id = :product_id')
            ->setParameter('product_id', $productId);

        return $query->fetchAllAssociative();
    }
}