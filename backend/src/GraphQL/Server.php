<?php

namespace App\GraphQL;

use App\Database\DatabaseConnection;
use App\GraphQL\Type\AttributeItemType;
use App\GraphQL\Type\AttributeType;
use App\GraphQL\Type\CategoryType;
use App\GraphQL\Type\CurrencyType;
use App\GraphQL\Type\PriceType;
use App\GraphQL\Type\ProductType;
use App\GraphQL\Type\QueryType;
use App\Repositories\AttributeItemRepository;
use App\Repositories\AttributeRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use Psr\Log\NullLogger;
use Throwable;
use Symfony\Component\String\Exception\RuntimeException;

class Server
{
    public static  function handle()
    {

        try {

            $conn = DatabaseConnection::getConnection();
            $logger = new NullLogger();
            $categoryRepository = new CategoryRepository($conn);
            $attributeItemRepository = new AttributeItemRepository($conn);
            $attributeRepository = new AttributeRepository($conn);
            $productRepository = new ProductRepository($conn);
            $orderItemRepository = new OrderItemRepository($conn, $logger);
            $orderRepository = new OrderRepository($conn, $logger, $orderItemRepository);
            $attributeItemType = new AttributeItemType();
            $currencyType = new CurrencyType();
            $priceType = new PriceType($currencyType);

            $categoryType = new ObjectType([
                'name' => 'Category',
                'fields' => [
                    'name' => Type::string()
                ]
            ]);

            $attributeType = new ObjectType([
                'name' => 'AttributeSet',
                'fields' => [
                    'id' => Type::string(),
                    'items' => [
                        'type' => Type::listOf($attributeItemType),
                        'resolve' => function ($attribute) use ($attributeItemRepository) {
                            return $attributeItemRepository->findAll($attribute['id']);
                        }
                    ],
                    'name' => ['type' => Type::string()],
                    'type' => ['type' => Type::string()],
                ]
            ]);

            $orderAttributeType = new ObjectType([
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

            $productType = new ObjectType([
                'name' => 'Product',
                'fields' => [
                    'id' => Type::string(),
                    'name' => Type::string(),
                    'inStock' => Type::boolean(),
                    'gallery' => Type::listOf(Type::string()),
                    'description' => Type::string(),
                    'category' => Type::string(),
                    'attributes' => [
                        'type' => Type::listOf($attributeType),
                        'resolve' => function ($product) use ($attributeRepository) {
                            return $attributeRepository->findAll($product['id']);
                        }
                    ],
                    'prices' => [
                        'type' => Type::listOf($priceType)
                    ],
                    'brand' => Type::string()
                ]
            ]);

            $queryType = new QueryType($categoryType, $categoryRepository, $productType, $productRepository);

            $orderProductType = new ObjectType([
                'name' => 'OrderProduct',
                'fields' => [
                    'id' => Type::string(),
                    'name' => Type::string(),
                    'gallery' => Type::listOf(Type::string()),
                    'description' => Type::string(),
                    'category' => Type::string(),
                    'attributes' => [
                        'type' => Type::listOf($orderAttributeType),
                        'resolve' => function ($product) use ($attributeRepository) {
                            return $attributeRepository->findAll($product['id']);
                        }
                    ],
                    'price' => $priceType,
                    'brand' => Type::string()
                ]
            ]);

            $orderResponseType = new ObjectType([
                'name' => 'OrderResponse',
                'fields' => [
                    'success' => Type::nonNull(Type::boolean()),
                    'orderRef' => Type::string(),
                    'message' => Type::string(),
                    'products' => Type::listOf($orderProductType),
                ]
            ]);


            $productInputType = new InputObjectType([
                'name' => 'OrderProductInput',
                'fields' => [
                    'productId' => Type::nonNull(Type::string()),
                    'price' => Type::nonNull($priceType),
                    'attributes' => Type::listOf($orderAttributeType),
                    'quantity' => Type::nonNull(Type::int()),
                ],
            ]);

            $mutationType = new ObjectType([
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

            // See docs on schema options:
            // https://webonyx.github.io/graphql-php/schema-definition/#configuration-options
            $schema = new Schema(
                (new SchemaConfig())
                    ->setQuery($queryType)
                    ->setMutation($mutationType)
            );

            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to get php://input');
            }

            $input = json_decode($rawInput, true);
            $query = $input['query'];
            $variableValues = $input['variables'] ?? null;

            $rootValue = ['prefix' => 'You said: '];
            $result = GraphQLBase::executeQuery($schema, $query, $rootValue, null, $variableValues);
            $output = $result->toArray();
        } catch (Throwable $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        return json_encode($output);
    }
}
