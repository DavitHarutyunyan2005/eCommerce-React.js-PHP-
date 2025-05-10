<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends AbstractRepository
{
    protected string $table = 'products';

    public function findAll(?string $category = null, ?string $madeFor = 'All'): array
    {
        $query = $this->db->createQueryBuilder()
            ->select(
                'p.id',
                'p.name',
                'p.description',
                'p.inStock',
                'p.brand',
                'p.madeFor',
                'c.name AS category'  // joining category name
            )
            ->from($this->table, 'p')
            ->innerJoin('p', 'categories', 'c', 'p.category_id = c.id');

        return $query->fetchAllAssociative();
    }

    public function insert(Product $product): void
    {
        $this->db->beginTransaction();


        try {

            // Getting the category_id
            $categoryQb = $this->db->createQueryBuilder();
            $categoryQb->select('id')
                ->from('categories')
                ->where('name = :name')
                ->setParameter('name', $product->getCategory()->getName());

            $categoryId = $categoryQb->fetchOne();

            if (!$categoryId) {
                throw new \Exception('Category not found: ' . $product->getCategory());
            }


            // Inserting the main product into the 'products' table
            $productQb = $this->db->createQueryBuilder();
            $productQb->insert('products')
                ->values([
                    'id' => ':id',
                    'name' => ':name',
                    'brand' => ':brand',
                    'description' => ':description',
                    'category_id' => ':category_id',
                    'inStock' => ':inStock',
                    'madeFor' => ':madeFor',
                ])
                ->setParameter('id', $product->getId())
                ->setParameter('name', $product->getName())
                ->setParameter('description', $product->getDescription())
                ->setParameter('category_id', $categoryId)
                ->setParameter('inStock', $product->isInStock())
                ->setParameter('madeFor', $product->getMadeFor())
                ->setParameter('brand', $product->getBrand())
                ->executeStatement();

            // Inserting attributes into 'attributes' table
            foreach ($product->getAttributes() as $attribute) {
                $attributesQb = $this->db->createQueryBuilder();
                $attributesQb->insert('attributes')
                    ->values([
                        'id' => ':id',
                        'product_id' => ':product_id',
                        'name' => ':name',
                        'type' => ':type',
                    ])
                    ->setParameter('id', $attribute->getId())
                    ->setParameter('product_id', $product->getId())
                    ->setParameter('name', $attribute->getName())
                    ->setParameter('type', $attribute->getType())
                    ->executeStatement();

                // Inserting attribute items into 'attribute_items' table
                foreach ($attribute->getItems() as $item) {
                    $itemQb = $this->db->createQueryBuilder();
                    $itemQb->insert('attribute_items')
                        ->values([
                            'id' => ':id',
                            'attribute_id' => ':attribute_id',
                            'value' => ':value',
                            'displayValue' => ':displayValue',
                        ])
                        ->setParameter('id', $item->getId())
                        ->setParameter('attribute_id', $attribute->getId())
                        ->setParameter('value', $item->getValue())
                        ->setParameter('displayValue', $item->getDisplayValue())
                        ->executeStatement();
                }
            }

            // Inserting gallery  into 'gallery' table
            foreach ($product->getGallery() as $imageUrl) {
                $qb = $this->db->createQueryBuilder();
                $qb->insert('gallery')
                    ->values([
                        'product_id' => ':product_id',
                        'image_url' => ':image_url',
                    ])
                    ->setParameter('product_id', $product->getId())
                    ->setParameter('image_url', $imageUrl)
                    ->executeStatement();
            }

            // Inserting price into 'prices' table
            foreach ($product->getPrices() as $price) {
                $qb = $this->db->createQueryBuilder();
                $qb->insert('prices')
                    ->values([
                        'id' => ':id',
                        'product_id' => ':product_id',
                        'amount' => ':amount',
                        'currency_label' => ':currency_label',
                        'currency_symbol' => ':currency_symbol',
                    ])
                    ->setParameter('id', $price->getId())
                    ->setParameter('product_id', $product->getId())
                    ->setParameter('amount', $price->getAmount())
                    ->setParameter('currency_label', $price->getCurrencyLabel())
                    ->setParameter('currency_symbol', $price->getCurrencySymbol())
                    ->executeStatement();
            }

            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
