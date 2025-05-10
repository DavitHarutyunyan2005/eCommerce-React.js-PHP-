<?php

declare(strict_types=1);

namespace App\Database;

use Exception;
use RuntimeException;
use Ramsey\Uuid\Uuid;

class Seeder
{
    public function run()
    {
        $conn = DatabaseConnection::getConnection();

        $jsonFile = __DIR__ . '/../../data/data.json';
        if (!file_exists($jsonFile)) {
            throw new RuntimeException("Error: Data file not found.");
        }

        $jsonData = file_get_contents($jsonFile);
        $data = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("JSON decoding error: " . json_last_error_msg());
        }

        try {
            $conn->beginTransaction();

            // Creating categories 
            foreach ($data['data']['categories'] as $category) {
                unset($category['__typename']);
                if (empty($category['id'])) {
                    $category['id'] = Uuid::uuid4()->toString(); 
                }
                $conn->insert('categories', $category);
            }

            // Inserting products
            foreach ($data['data']['products'] as $product) {
                unset($product['__typename']);
                $prices = $product['prices'];
                unset($product['prices']);

                $attributes = $product['attributes'];
                unset($product['attributes']);

                $gallery = $product['gallery'];
                unset($product['gallery']);

                $categoryId = $conn->fetchOne('SELECT id FROM categories WHERE name = ?', [$product['category']]);
                if (!$categoryId) {
                    throw new Exception("Category not found: " . $product['category']);
                }
                $product['category_id'] = $categoryId;
                unset($product['category']);

                $conn->insert('products', $product);

                foreach ($attributes as $attribute) {
                    unset($attribute['__typename']);
                    $attribute['id'] = Uuid::uuid4()->toString();
                    $attribute['product_id'] = $product['id'];
                    $items = $attribute['items'];
                    unset($attribute['items']);

                    $conn->insert('attributes', $attribute);

                    foreach ($items as $item) {
                        unset($item['__typename']);
                        $item['id'] = Uuid::uuid4()->toString();
                        $item['attribute_id'] = $attribute['id'];
                        $conn->insert('attribute_items', $item);
                    }
                }

                foreach ($prices as $price) {
                    unset($price['__typename']);
                    $price['id'] = Uuid::uuid4()->toString();
                    $price['currency_label'] = $price['currency']['label'];
                    $price['currency_symbol'] = $price['currency']['symbol'];
                    unset($price['currency']);

                    $price['product_id'] = $product['id'];

                    $conn->insert('prices', $price);
                }

                foreach ($gallery as $imageUrl) {
                    $image = [
                        'product_id' => $product['id'],
                        'image_url' => $imageUrl,
                    ];

                    $conn->insert('gallery', $image);
                }
            }

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollBack();
            throw new RuntimeException("Seeding failed: " . $e->getMessage());
        }
    }
}
