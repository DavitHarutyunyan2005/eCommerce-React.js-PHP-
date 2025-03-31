<?php

namespace Repositories;


class GalleryRepository extends AbstractRepository
{
    protected string $table = 'gallery';

    public function findAll(?string $productId = null): array
    {
        if ($productId === null) {
            throw new \InvalidArgumentException("Product ID must be provided.");
        }

        $query = $this->db->createQueryBuilder()
            ->select('g.image_url')
            ->from($this->table, 'g')
            ->where('g.product_id = :product_id')
            ->setParameter('product_id', $productId);

        return $query->fetchAllAssociative();
    }
}