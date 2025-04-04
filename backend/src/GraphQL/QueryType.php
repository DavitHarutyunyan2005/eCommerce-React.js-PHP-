<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;


class QueryType extends ObjectType
{
    public function __construct(CategoryType $categoryType, CategoryRepository $categoryRepository, ProductType $productType, ProductRepository $productRepository)
    {
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
                    'resolve' => function () use ($productRepository) {
                        return $productRepository->findAll();
                    }
                ],
            ]
        ]);
    }
}
