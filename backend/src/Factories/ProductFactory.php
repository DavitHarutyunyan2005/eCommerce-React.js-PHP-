<?php

namespace App\Factories;

use App\Models\Product;
use App\Factories\AttributeFactory;
use App\Factories\CategoryFactory;
use App\Factories\MadeForFactory;
/**
 * ProductFactory is responsible for creating instances of the Product class.
 */

class ProductFactory
{
    private AttributeFactory $attributeFactory;
    private CategoryFactory $categoryFactory;
    private MadeForFactory $madeForFactory;

    public function __construct(
        AttributeFactory $attributeFactory,
        CategoryFactory $categoryFactory,
        MadeForFactory $madeForFactory
    ) {
        $this->attributeFactory = $attributeFactory;
        $this->categoryFactory = $categoryFactory;
        $this->madeForFactory = $madeForFactory;
    }

    public function create(array $data): Product
    {
        $category = $this->categoryFactory->create($data['category']->getName());
        $madeFor = $this->madeForFactory->create($data['madeFor']->getName());

        $attributes = [];
        foreach ($data['attributes'] as $attributeData) {
            $attributes[] = $this->attributeFactory->create($attributeData);
        }

        return new Product(
            id: $data['id'],
            name: $data['name'],
            inStock: $data['inStock'],
            gallery: $data['gallery'], 
            description: $data['description'],
            category: $category,
            attributes: $attributes,
            prices: $data['prices'], 
            brand: $data['brand'], 
            madeFor: $madeFor
        );
    }
}
