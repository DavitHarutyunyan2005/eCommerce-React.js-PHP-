<?php

namespace App\GraphQL\Type\Order;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class ProductInputType extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'OrderProductInput',
            'fields' => [
                'productId' => Type::nonNull(Type::string()),
                'priceId' => Type::nonNull(Type::string()),
                'attributeItemIds' => Type::listOf(Type::string()),
                'quantity' => Type::nonNull(Type::int()),
            ],
        ]);
    }
}