<?php

namespace App\Database\Seeder;

use App\Database\DatabaseConnection;
use Exception;
use Ramsey\Uuid\Guid\Guid;

class Seeder
{
    public static function seed()
    {
        $conn = DatabaseConnection::getConnection();
        echo "Using Connection: " . get_class($conn) . PHP_EOL;

        $jsonFile = __DIR__ . '/../../../data/data.json';
        if (!file_exists($jsonFile)) {
            die("Error: Data file not found.\n");
        }

        $jsonData = file_get_contents($jsonFile);
        $data = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die("JSON decoding error: " . json_last_error_msg() . "\n");
        }

        try {
            $conn->beginTransaction();

            // Create categories first
            foreach ($data['data']['categories'] as $category) {

                unset($category['__typename']);
                if (empty($category['id'])) {
                    $category['id'] = Guid::uuid4()->toString(); // Generate UUID for categories
                }
                $conn->insert('categories', $category);

            }


            //INSERT PRODUCTS:
            foreach ($data['data']['products'] as $product) {

                unset($product['__typename']);
                $prices = $product['prices'];
                unset($product['prices']);

                $attributes = $product['attributes'];
                unset($product['attributes']);

                $gallery = $product['gallery'];
                unset($product['gallery']);

                // Get the category_id from the category name
                $categoryId = $conn->fetchOne('SELECT id FROM categories WHERE name = ?', [$product['category']]);
                if (!$categoryId) {
                    throw new Exception("Category not found: " . $product['category']);
                }
                $product['category_id'] = $categoryId;


                // INSERT PRODUCT:
                $conn->insert('products', $product);

                //INSERT ATTRIBUTES:
                foreach ($attributes as $attribute) {

                    unset($attribute['__typename']);
                    $attribute['id'] = Guid::uuid4()->toString();
                    $attribute['product_id'] = $product['id']; // Foreign key to products
                    $items = $attribute['items'];
                    unset($attribute['items']);

                    $conn->insert('attributes', $attribute);

                    foreach ($items as $item) {

                        unset($item['__typename']);
                        $item['id'] = Guid::uuid4()->toString();
                        $item['attribute_id'] = $attribute['id']; // Foreign key to attributes
                        $conn->insert('attribute_items', $item);
                    }
                }

                //INSERT PRICES:
                foreach ($prices as $price) {

                    unset($price['__typename']);
                    $price['id'] = Guid::uuid4()->toString();
                    $price['currency_label'] = $price['currency']['label'];
                    $price['currency_symbol'] = $price['currency']['symbol'];
                    unset($price['currency']);

                    $price['product_id'] = $product['id'];

                    $conn->insert('prices', $price);
                }

                //INSERT GALLERY:
                foreach ($gallery as $imageUrl) {

                    $image['product_id'] = $product['id'];
                    $image['image_url'] = $imageUrl;

                    $conn->insert('gallery', $image);
                }
            }

            $conn->commit();
            echo "Database seeded successfully.\n";
        } catch (Exception $e) {
            $conn->rollBack();
            die("Seeding failed: " . $e->getMessage() . "\n");
        }
    }
}
