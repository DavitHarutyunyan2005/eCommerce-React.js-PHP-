<?php

namespace App\GraphQL\Type\Order;

use App\GraphQL\Type\Attribute\AttributeItemType;
use App\Repositories\AttributeItemRepository;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class OrderAttributeType extends ObjectType
{
    public function __construct(
        AttributeItemType $attributeItemType,
        AttributeItemRepository $attributeItemRepository
    ) {
        parent::__construct([
            'name' => 'OrderAttributeSet',
            'fields' => [
                'id' => Type::string(),
                'item' => [
                    'type' => $attributeItemType,
                    'resolve' => function ($attribute) use ($attributeItemRepository) {
                        return $attributeItemRepository->findById($attribute['id']);
                    }
                ],
                'name' => ['type' => Type::string()],
                'type' => ['type' => Type::string()],
            ]
        ]);
    }
}
