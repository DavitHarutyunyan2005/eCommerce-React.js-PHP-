<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\Repositories\OrderRepository;

class OrderMutation extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Mutation',
            'fields' => [
                'createOrder' => [
                    'type' => Type::boolean(),
                    'args' => [
                        'productId' => Type::int(),
                        'quantity' => Type::int(),
                    ],
                    'resolve' => function ($root, $args) {
                        return OrderRepository::create($args['productId'], $args['quantity']);
                    }
                ]
            ]
        ]);
    }
}
