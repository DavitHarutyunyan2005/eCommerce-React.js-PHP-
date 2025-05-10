<?php

namespace App\GraphQL\Type\Attribute;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\Repositories\AttributeItemRepository;


class AttributeType extends ObjectType
{
    public function __construct(AttributeItemRepository $attributeItemRepository, AttributeItemType $attributeItemType)
    {
        parent::__construct([
            'name' => 'AttributeSet',
            'fields' => [
                'id' => Type::string(),
                'items' => [
                    'type' => Type::listOf($attributeItemType),
                    'resolve' => function ($attribute) use ($attributeItemRepository) {
                        return $attributeItemRepository->findAll($attribute['id']);
                    }
                ],
                'name' => Type::string(),
                'type' => Type::string(),
            ]
        ]);
    }
}
