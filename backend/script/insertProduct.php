<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\DatabaseConnection;
use App\Repositories\ProductRepository;
use Ramsey\Uuid\Guid\Guid;
use App\Factories\ProductFactory;
use App\Factories\AttributeFactory;
use App\Factories\CategoryFactory;
use App\Factories\MadeForFactory;
use App\Models\Price;
use App\Models\Attribute\AttributeItem;

// SINCE THERE ISN'T AN ADMIN PANEL IN FRONTEND, 
// THIS SCRIPT IS AN EXAMPLE TO INSERT PRODUCTS INTO THE DATABASE PROGRAMMATICALLY

try {

    $attributeFactory = new AttributeFactory();
    $categoryFactory = new CategoryFactory();
    $madeForFactory = new MadeForFactory();

    $productFactory = new ProductFactory(
        $attributeFactory,
        $categoryFactory,
        $madeForFactory
    );

    $conn = DatabaseConnection::getConnection();
    $productRepository = new ProductRepository($conn);

    $prices = [
        new Price(Guid::uuid4()->toString(), 30.00,  'USD', '$'),
    ];

    $attributeColorItems = [
        new AttributeItem(Guid::uuid4()->toString(), 'Green', '#BEBEBE'),
        new AttributeItem(Guid::uuid4()->toString(), 'Black', '#000000'),
    ];

    $attributeSizeItems = [
        new AttributeItem(Guid::uuid4()->toString(), 'S', 'Small'),
        new AttributeItem(Guid::uuid4()->toString(), 'M', 'Medium'),
        new AttributeItem(Guid::uuid4()->toString(), 'L', 'Large'),
        new AttributeItem(Guid::uuid4()->toString(), 'XL', 'Extra Large'),
    ];

    $attributeData = [
        [
            'id' => Guid::uuid4()->toString(),
            'name' => 'Color',
            'type' => 'swatch',
            'items' => $attributeColorItems
        ],
        [
            'id' => Guid::uuid4()->toString(),
            'name' => 'Size',
            'type' => 'text',
            'items' => $attributeSizeItems
        ]
    ];

    $categoryClass = $categoryFactory->create('clothes');
    $madeForClass = $madeForFactory->create('Men');

    $data = [
        'id' => 'jackets-windbreaker-baseball',
        'name' => "Jackets Windbreaker Baseball",
        'inStock' => true,
        'gallery' => [
            'https://m.media-amazon.com/images/I/31dNUnDaVQL._AC_SR400,400_.jpg'
        ],
        'description' => "MAGNIVIT Mens Jackets Windbreaker Baseball Bomber Flight Jacket Mens Winter Coats",
        'category' => $categoryClass,
        'attributes' => $attributeData,
        'prices' => $prices,
        'brand' => 'Magnivit',
        'madeFor' => $madeForClass
    ];

    $product = $productFactory->create($data);

    $productRepository->insert($product);
    echo "Product inserted successfully with ID: " . $product->getId() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conn->close();
}