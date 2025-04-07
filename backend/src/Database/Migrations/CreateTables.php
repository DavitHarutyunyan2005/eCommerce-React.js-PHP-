<?php

namespace App\Database\Migrations;

use App\Database\DatabaseConnection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

class CreateTables
{
    private $conn;

    public function __construct()
    {
        $this->conn = DatabaseConnection::getConnection();
    }

    public function create()
    {
        // Creating the schema
        $schema = new Schema();

        // Defining the categories table
        $this->createCategoriesTable($schema);

        // Defining the products table
        $this->createProductsTable($schema);

        // Defining the attributes table
        $this->createAttributesTable($schema);

        // Defining the attribute items table
        $this->createAttributeItemsTable($schema);

        // Defining the prices table
        $this->createPricesTable($schema);

        //Defining the gallery table
        $this->createGalleryTable($schema);

        // Defining the orders table
        $this->createOrdersTable($schema);

        // Defining the order items table
        $this->createOrderItemsTable($schema);

        // Executing the schema update
        $this->applySchema($schema);
    }

    private function createCategoriesTable(Schema $schema)
    {
        $categoriesTable = $schema->createTable('categories');
        $categoriesTable->addColumn('id', Types::GUID, ['length' => 36]);
        $categoriesTable->addColumn('name', Types::STRING, ['length' => 255]);
        $categoriesTable->setPrimaryKey(['id']);
    }

    private function createProductsTable(Schema $schema)
    {
        $productsTable = $schema->createTable('products');
        $productsTable->addColumn('id', Types::STRING, ['length' => 255]);
        $productsTable->addColumn('name', Types::STRING, ['length' => 255]);
        $productsTable->addColumn('description', Types::TEXT, ['notnull' => false]);
        $productsTable->addColumn('category', Types::STRING, ['length' => 255]);
        $productsTable->addColumn('category_id', Types::GUID); // Foreign key to categories
        $productsTable->addColumn('inStock', Types::BOOLEAN, ['default' => true]);
        $productsTable->addColumn('brand', Types::STRING, ['length' => 255]);
        $productsTable->setPrimaryKey(['id']);
        $productsTable->addIndex(['category_id'], 'idx_category_id'); // foreign key to categories

        $productsTable->addForeignKeyConstraint(
            'categories',
            ['category_id'],
            ['id'],
            ['onDelete' => 'CASCADE']
        );
    }

    private function createAttributesTable(Schema $schema)
    {
        $attributesTable = $schema->createTable('attributes');
        $attributesTable->addColumn('id', Types::STRING, ['length' => 255]);
        $attributesTable->addColumn('product_id', Types::STRING, ['length' => 255]);
        $attributesTable->addColumn('name', Types::STRING, ['length' => 255]);
        $attributesTable->addColumn('type', Types::STRING, ['length' => 50]);
        $attributesTable->setPrimaryKey(['id']);
        $attributesTable->addIndex(['product_id'], 'idx_product_id_in_attributes'); // foreign key to products

        $attributesTable->addForeignKeyConstraint('products', ['product_id'], ['id'], ['onDelete' => 'CASCADE']);
    }

    private function createAttributeItemsTable(Schema $schema)
    {
        $attributeItemsTable = $schema->createTable('attribute_items');
        $attributeItemsTable->addColumn('id', Types::STRING, ['length' => 255]);
        $attributeItemsTable->addColumn('attribute_id', Types::STRING, ['length' => 255]);
        $attributeItemsTable->addColumn('value', Types::STRING, ['length' => 255]);
        $attributeItemsTable->addColumn('displayValue', Types::STRING, ['length' => 255]);
        $attributeItemsTable->setPrimaryKey(['id']);
        $attributeItemsTable->addIndex(['attribute_id'], 'idx_attribute_id'); // foreign key to attributes

        $attributeItemsTable->addForeignKeyConstraint('attributes', ['attribute_id'], ['id']);
    }

    private function createPricesTable(Schema $schema)
    {
        $pricesTable = $schema->createTable('prices');
        $pricesTable->addColumn('id', Types::STRING, ['length' => 255]);
        $pricesTable->addColumn('product_id', Types::STRING, ['length' => 255]); // Foreign key to products
        $pricesTable->addColumn('amount', Types::FLOAT);
        $pricesTable->addColumn('currency_label', Types::STRING, ['length' => 50]);
        $pricesTable->addColumn('currency_symbol', Types::STRING, ['length' => 50]);
        $pricesTable->setPrimaryKey(['id']);
        $pricesTable->addIndex(['product_id'], 'idx_product_id_in_prices'); // foreign key to products

        $pricesTable->addForeignKeyConstraint('products', ['product_id'], ['id'], ['onDelete' => 'CASCADE']);
    }

    private function createGalleryTable(Schema $schema)
    {
        $galleryTable = $schema->createTable('gallery');
        $galleryTable->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $galleryTable->addColumn('product_id', Types::STRING, ['length' => 255]);
        $galleryTable->addColumn('image_url', Types::STRING, ['length' => 255]);
        $galleryTable->setPrimaryKey(['id']);
        $galleryTable->addIndex(['product_id'], 'idx_product_id_in_gallery');

        $galleryTable->addForeignKeyConstraint('products', ['product_id'], ['id'], ['onDelete' => 'CASCADE']);
    }

    private function createOrdersTable(Schema $schema)
    {
        $ordersTable = $schema->createTable('orders');
        $ordersTable->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $ordersTable->addColumn('order_ref', Types::STRING, ['length' => 255]);
        $ordersTable->addColumn('created_at', Types::DATETIME_IMMUTABLE);

        $ordersTable->setPrimaryKey(['id']);
    }

    private function createOrderItemsTable(Schema $schema)
    {
        $orderItemsTable = $schema->createTable('order_items');
        $orderItemsTable->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $orderItemsTable->addColumn('order_id', Types::STRING, ['length' => 255, 'notnull' => true]);
        $orderItemsTable->addColumn('product_id', Types::STRING, ['length' => 255, 'notnull' => true]);
        $orderItemsTable->addColumn('price_id', Types::STRING, ['length' => 255, 'notnull' => true]);
        $orderItemsTable->addColumn('attribute_item_id', Types::STRING, ['length' => 255, 'notnull' => true]);
        $orderItemsTable->addColumn('quantity', Types::INTEGER, ['notnull' => true]);

        $orderItemsTable->setPrimaryKey(['id']);

        $orderItemsTable->addIndex(['order_id'], 'idx_order_id');
        $orderItemsTable->addIndex(['product_id'], 'idx_product_id_in_order_items');
        $orderItemsTable->addIndex(['price_id'], 'idx_price_id');
        $orderItemsTable->addIndex(['attribute_item_id'], 'idx_attribute_item_id');
        
        $orderItemsTable->addForeignKeyConstraint('orders', ['order_id'], ['id'], ['onDelete' => 'CASCADE']);
        $orderItemsTable->addForeignKeyConstraint('prices', ['price_id'], ['id'], ['onDelete' => 'CASCADE']);
        $orderItemsTable->addForeignKeyConstraint('attribute_items', ['attribute_item_id'], ['id'], ['onDelete' => 'CASCADE']);
        $orderItemsTable->addForeignKeyConstraint('products', ['product_id'], ['id'], ['onDelete' => 'CASCADE']);
    }

    private function applySchema(Schema $schema)
    {
        $schemaManager = $this->conn->createSchemaManager();

        $schemaManager->createTable($schema->getTable('categories'));
        $schemaManager->createTable($schema->getTable('products'));
        $schemaManager->createTable($schema->getTable('attributes'));
        $schemaManager->createTable($schema->getTable('attribute_items'));
        $schemaManager->createTable($schema->getTable('prices'));
        $schemaManager->createTable($schema->getTable('gallery'));
        $schemaManager->createTable($schema->getTable('orders'));
        $schemaManager->createTable($schema->getTable('order_items'));
    }
}
