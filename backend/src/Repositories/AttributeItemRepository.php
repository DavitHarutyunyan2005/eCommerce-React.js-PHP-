<?php

namespace App\Repositories;

class AttributeItemRepository extends AbstractRepository
{
    protected string $table = 'attribute_items';

    public function findAll(?string $attributeId = null): array
    {
        if ($attributeId === null) {
            throw new \InvalidArgumentException("Attribute ID must be provided.");
        }

        try {
            $query = $this->db->createQueryBuilder()
                ->select('a.value, a.displayValue, a.id')
                ->from($this->table, 'a')
                ->where('attribute_id = :attributeId')
                ->setParameter('attributeId', $attributeId);

            $result = $query->fetchAllAssociative();

            return $result ?: [];
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to fetch attribute items: " . $e->getMessage());
        }
    }

    public function findById(string $id): array
    {
        try {
            $query = $this->db->createQueryBuilder()
                ->select('a.value, a.displayValue')
                ->from($this->table, 'a')
                ->where('a.id = :id')
                ->setParameter('id', $id);

            $result = $query->fetchAssociative();

            return $result ?: [];
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to fetch attribute item by ID: " . $e->getMessage());
        }
    }
}
