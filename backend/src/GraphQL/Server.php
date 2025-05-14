<?php

namespace App\GraphQL;

use App\Database\DatabaseConnection;
use App\GraphQL\Type\Attribute\AttributeItemType;
use App\GraphQL\Type\Attribute\AttributeType;
use App\GraphQL\Type\CategoryType;
use App\GraphQL\Type\Order\ProductResponseType;
use App\GraphQL\Type\Price\CurrencyType;
use App\GraphQL\Type\Order\OrderResponseType;
use App\GraphQL\Type\Price\PriceType;
use App\GraphQL\Type\Order\ProductInputType;
use App\GraphQL\Type\ProductType;
use App\GraphQL\Type\QueryType;
use App\Repositories\AttributeItemRepository;
use App\Repositories\AttributeRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\GalleryRepository;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PricesRepository;
use App\Repositories\ProductRepository;
use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use Psr\Log\NullLogger;
use Throwable;
use Symfony\Component\String\Exception\RuntimeException;


class Server
{
    public static function handle()
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
            $pricesRepository = new PricesRepository($conn);
            $galleryRepository = new GalleryRepository($conn);

            $attributeItemType = new AttributeItemType();
            $currencyType = new CurrencyType();
            $priceType = new PriceType($currencyType);
            $categoryType = new CategoryType();
            $attributeType = new AttributeType($attributeItemRepository, $attributeItemType);
            $productType = new ProductType($attributeType, $attributeRepository, $priceType, $pricesRepository, $galleryRepository);
            $productInputType = new ProductInputType();
            $productResponseType = new ProductResponseType();
            $orderResponseType = new OrderResponseType($productResponseType);

            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => [
                    'insertOrder' => [
                        'type' => $orderResponseType,
                        'args' => [
                            'products' => Type::nonNull(Type::listOf($productInputType)),
                        ],
                        'resolve' => static function ($root, $args) use ($orderRepository) {
                            return $orderRepository->insertOrder($args['products']);
                        },
                    ],
                ],
            ]);


            $queryType = new QueryType($categoryType, $categoryRepository, $productType, $productRepository);
            $schema = new Schema((new SchemaConfig())->setQuery($queryType)->setMutation($mutationType));

            $rawInput = file_get_contents('php://input');
            if ($rawInput === false || trim($rawInput) === '') {
                throw new RuntimeException('Empty or invalid input from php://input');
            }

            $input = json_decode($rawInput, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException('Invalid JSON input: ' . json_last_error_msg());
            }

            if (!isset($input['query'])) {
                throw new RuntimeException('Missing query field in request.');
            }

            $query = $input['query'];
            $variableValues = $input['variables'] ?? null;
            $rootValue = null;

            $result = GraphQLBase::executeQuery($schema, $query, $rootValue, null, $variableValues);
            $output = $result->toArray();

            if (isset($output['errors'])) {
                foreach ($output['errors'] as &$error) { 
                    if (isset($error['extensions']['exception']['message'])) {
                        $error['message'] = $error['extensions']['exception']['message'];
                    }
                }
                unset($error); 
            }

            echo json_encode($output);
            exit;
        } catch (Throwable $e) {
            echo json_encode([
                'errors' => [
                    ['message' => $e->getMessage()]
                ]
            ]);
            exit;
        }
    }
}