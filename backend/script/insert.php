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
        new Price(Guid::uuid4()->toString(), 32.00,  'USD', '$'),
    ];

    $attributeColorItems = [
        new AttributeItem(Guid::uuid4()->toString(), 'Green', '#006262'),
        new AttributeItem(Guid::uuid4()->toString(), 'Red', '#F33131'),
    ];

    $attributeSizeItems = [
        new AttributeItem(Guid::uuid4()->toString(), 'XS', ' Extra Small'),
        new AttributeItem(Guid::uuid4()->toString(), 'S', 'Small'),
        new AttributeItem(Guid::uuid4()->toString(), 'M', 'Medium'),
        // new AttributeItem(Guid::uuid4()->toString(), 'XL', 'Extra Large'),
    ];

    $attributeData = [
        // [
        //     'id' => Guid::uuid4()->toString(),
        //     'name' => 'Color',
        //     'type' => 'swatch',
        //     'items' => $attributeColorItems
        // ],
        [
            'id' => Guid::uuid4()->toString(),
            'name' => 'Size',
            'type' => 'text',
            'items' => $attributeSizeItems
        ]
    ];

    $categoryClass = $categoryFactory->create('clothes');
    $madeForClass = $madeForFactory->create('Kids');

    $data = [
        'id' => 'top-floral-skort-set',
        'name' => "Top & Floral Skort Set",
        'inStock' => true,
        'gallery' => [
            'https://slimages.macysassets.com/is/image/MCY/products/5/optimized/31535745_fpx.tif?op_sharpen=1&wid=500&fit=fit,1&fmt=webp',
            'https://slimages.macysassets.com/is/image/MCY/products/6/optimized/31535746_fpx.tif?op_sharpen=1&wid=500&fit=fit,1&fmt=webp',
            'https://slimages.macysassets.com/is/image/MCY/products/7/optimized/31535747_fpx.tif?op_sharpen=1&wid=500&fit=fit,1&fmt=webp'
        ],
        'description' => "Shirt: square neckline; front buttons; flutter sleeves; ruffled hem
Skort: pulls on; allover floral print; inner shorts
Top: cotton/polyester/elastane; skort: cotton",
        'category' => $categoryClass,
        'attributes' => $attributeData,
        'prices' => $prices,
        'brand' => "Carter's",
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
