<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\Repositories\AttributeRepository;

class ProductType extends ObjectType
{
    public function __construct(
        AttributeType $attributeType,
        AttributeRepository $attributeRepository,
        PriceType $priceType
    ) {
        parent::__construct([
            'name' => 'Product',
            'fields' => [
                'id' => Type::string(),
                'name' => Type::string(),
                'inStock' => Type::boolean(),
                'gallery' => Type::listOf(Type::string()),
                'description' => Type::string(),
                'category' => Type::string(),
                'attributes' => [
                    'type' => Type::listOf($attributeType),
                    'resolve' => function ($product) use ($attributeRepository) {
                        return $attributeRepository->findAll($product['id']);
                    }
                ],
                'prices' => [
                    'type' => Type::listOf($priceType)
                ],
                'brand' => Type::string()
            ]
        ]);
    }
}
