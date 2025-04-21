<?php

namespace App\GraphQL\Type\Order;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class OrderResponseType extends ObjectType
{
    public function __construct(
        OrderProductType $orderProductType
    ) {
        parent::__construct([
            'name' => 'OrderResponse',
            'fields' => [
                'success' => Type::nonNull(Type::boolean()),
                'orderRef' => Type::string(),
                'message' => Type::string(),
                'products' => Type::listOf($orderProductType),
            ]
        ]);
    }
}
