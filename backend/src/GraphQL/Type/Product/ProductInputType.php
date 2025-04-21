<?php

namespace App\GraphQL\Type\Product;

use App\GraphQL\Type\Order\OrderAttributeType;
use App\GraphQL\Type\Price\PriceType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class ProductInputType extends InputObjectType
{
    public function __construct(
        PriceType $priceType,
        OrderAttributeType $orderAttributeType
    )
    {
        parent::__construct([
            'name' => 'OrderProductInput',
            'fields' => [
                'productId' => Type::nonNull(Type::string()),
                'price' => Type::nonNull($priceType),
                'attributes' => Type::listOf($orderAttributeType),
                'quantity' => Type::nonNull(Type::int()),
            ],
        ]);
    }
}