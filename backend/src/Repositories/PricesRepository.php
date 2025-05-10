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
            ->select('p.id, p.amount, p.currency_label, p.currency_symbol')
            ->from($this->table, 'p')
            ->where('p.product_id = :product_id')
            ->setParameter('product_id', $productId);

        $results = $query->fetchAllAssociative();

        // Transforming each row to match expected structure
        $transformed = array_map(function ($row) {
            return [
                'id' => $row['id'],
                'amount' => (float) $row['amount'],
                'currency' => [
                    'label' => $row['currency_label'],
                    'symbol' => $row['currency_symbol'],
                ]
            ];
        }, $results);

        return $transformed;
    }
}
