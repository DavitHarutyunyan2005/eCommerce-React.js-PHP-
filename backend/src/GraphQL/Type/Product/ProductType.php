<?php

namespace App\GraphQL\Type\Product;

use App\GraphQL\Type\Attribute\AttributeType;
use App\GraphQL\Type\Price\PriceType;
use App\Repositories\GalleryRepository;
use App\Repositories\PricesRepository;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\Repositories\AttributeRepository;

class ProductType extends ObjectType
{
    public function __construct(
        AttributeType $attributeType,
        AttributeRepository $attributeRepository,
        PriceType $priceType,
        PricesRepository $pricesRepository,
        GalleryRepository $galleryRepository
    ) {
        parent::__construct([
            'name' => 'Product',
            'fields' => [
                'id' => Type::string(),
                'name' => Type::string(),
                'inStock' => Type::boolean(),
                'gallery' => [
                    'type' => Type::listOf(Type::string()),
                    'resolve' => function ($product) use ($galleryRepository) {
                        return $galleryRepository->findAll($product['id']);
                    }
                ],
                'description' => Type::string(),
                'category' => Type::string(),
                'attributes' => [
                    'type' => Type::listOf($attributeType),
                    'resolve' => function ($product) use ($attributeRepository) {
                        return $attributeRepository->findAll($product['id']);
                    }
                ],
                'prices' => [
                    'type' => Type::listOf($priceType),
                    'resolve' => function ($product) use ($pricesRepository) {
                        return $pricesRepository->findAll($product['id']);
                    }
                ],
                'brand' => Type::string(),
                'madeFor' => Type::string(),
            ]
        ]);
    }
}
