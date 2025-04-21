<?php

namespace App\GraphQL\Type;

use App\GraphQL\Type\Product\ProductType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;

class QueryType extends ObjectType
{
    public function __construct(
        CategoryType $categoryType,
        CategoryRepository $categoryRepository,
        ProductType $productType,
        ProductRepository $productRepository
    ) {
        parent::__construct([
            'name' => 'Query',
            'fields' => [
                'categories' => [
                    'type' => Type::listOf($categoryType),
                    'resolve' => function () use ($categoryRepository) {
                        return $categoryRepository->findAll();
                    }
                ],
                'products' => [
                    'type' => Type::listOf($productType),
                    'resolve' => function ($root, $args) use ($productRepository) {
                        return $productRepository->findAll();
                    }
                ],
                'product' => [
                    'type' => $productType,
                    'args' => [
                        'id' => Type::nonNull(Type::string()), 
                    ],
                    'resolve' => function ($root, $args) use ($productRepository) {
                        return $productRepository->findById($args['id']);
                    }
                ],
            ]
        ]);
    }
}