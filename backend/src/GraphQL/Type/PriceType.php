<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class PriceType extends ObjectType
{
    public function __construct( CurrencyType $currencyType)
    {
        parent::__construct([
            'name' => 'Price',
            'fields' => [
                'amount' => Type::float(),
                'currency' => Type::listOf($currencyType)
                ]
        ]);
    }
}
