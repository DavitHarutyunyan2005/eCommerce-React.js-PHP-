<?php

namespace App\GraphQL\Type\Price;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class PriceType extends ObjectType
{
    public function __construct( CurrencyType $currencyType)
    {
        parent::__construct([
            'name' => 'Price',
            'fields' => [
                'id' => Type::nonNull(Type::string()),
                'amount' => Type::float(),
                'currency' => $currencyType
                ]
        ]);
    }
}
