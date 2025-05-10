<?php

namespace App\GraphQL\Type\Order;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProductResponseType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'ProductOrder',
            'fields' => [
                'productId' => Type::nonNull(Type::string()),
                'priceId' => Type::nonNull(Type::string()),
                'attributeItemIds' => Type::listOf(Type::string()),
                'quantity' => Type::nonNull(Type::int()),
            ]
        ]);
    }
}
