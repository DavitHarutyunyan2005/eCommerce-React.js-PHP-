<?php

namespace App\GraphQL\Type\Attribute;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AttributeItemType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Attribute',
            'fields' => [
                'displayValue' => Type::string(),
                'value' => Type::string(),
                'id' =>  Type::string()
            ]
        ]);
    }
}
