<?php

namespace App\GraphQL\Type;

use App\GraphQL\Type\Order\OrderResponseType;
use App\GraphQL\Type\Product\ProductInputType;
use App\Repositories\OrderRepository;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class MutationType extends ObjectType
{
    public function __construct(
        OrderResponseType $orderResponseType,
        ProductInputType $productInputType,
        OrderRepository $orderRepository
    )
    {
        parent::__construct([
            'name' => 'Mutation',
            'fields' => [
                'insertOrder' => [
                    'type' => $orderResponseType,
                    'args' => [
                        'products' => Type::nonNull(Type::listOf($productInputType))
                    ],
                    'resolve' => static function ($root, $args) use ($orderRepository) {
                        return $orderRepository->insertOrder($args['products']);
                    },
                ],
            ],
        ]);
    }
}
