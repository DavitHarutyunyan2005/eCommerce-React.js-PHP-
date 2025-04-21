<?php

namespace App\GraphQL\Type\Order;

use App\GraphQL\Type\Price\PriceType;
use App\Repositories\AttributeRepository;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class OrderProductType extends ObjectType
{
    public function __construct(
        OrderAttributeType $orderAttributeType,
        AttributeRepository $attributeRepository,
        PriceType $priceType
    ) {
        parent::__construct([
            'name' => 'OrderProduct',
            'fields' => [
                'id' => Type::string(),
                'name' => Type::string(),
                'gallery' => Type::listOf(Type::string()),
                'description' => Type::string(),
                'category' => Type::string(),
                'attributes' => [
                    'type' => Type::listOf($orderAttributeType),
                    'resolve' => function ($product) use ($attributeRepository) {
                        return $attributeRepository->findAll($product['id']);
                    }
                ],
                'price' => $priceType,
                'brand' => Type::string()
            ]
        ]);
    }
}
