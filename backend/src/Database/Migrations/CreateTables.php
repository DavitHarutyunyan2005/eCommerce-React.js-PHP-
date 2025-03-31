<?php

namespace Database\Migrations;

use Database\DatabaseConnection;
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

        // Executing the schema update
        $this->applySchema($schema);
    }

    private function createCategoriesTable(Schema $schema)
    {
        $categoriesTable = $schema->createTable('categories');
        $categoriesTable->addColumn('id', Types::GUID, ['length' => 36]);
        $categoriesTable->addColumn('name', Types::STRING, ['length' => 255]);
        $categoriesTable->addColumn('__typename', Types::STRING, ['length' => 50]);
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
        $productsTable->addColumn('__typename', Types::STRING, ['length' => 50]);
        $productsTable->setPrimaryKey(['id']);
        
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
        $attributesTable->addColumn('__typename', Types::STRING, ['length' => 50]); 
        $attributesTable->setPrimaryKey(['id']);

        $attributesTable->addForeignKeyConstraint('products', ['product_id'], ['id'], ['onDelete' => 'CASCADE']);
    }

    private function createAttributeItemsTable(Schema $schema)
    {
        $attributeItemsTable = $schema->createTable('attribute_items');
        $attributeItemsTable->addColumn('id', Types::STRING, ['length' => 255]);
        $attributeItemsTable->addColumn('attribute_id', Types::STRING, ['length' => 255]); 
        $attributeItemsTable->addColumn('value', Types::STRING, ['length' => 255]);
        $attributeItemsTable->addColumn('displayValue', Types::STRING, ['length' => 255]);
        $attributeItemsTable->addColumn('__typename', Types::STRING, ['length' => 50]);
        $attributeItemsTable->setPrimaryKey(['id']);
        
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
        $pricesTable->addColumn('__typename', Types::STRING, ['length' => 50]);
        $pricesTable->setPrimaryKey(['id']);
        
        $pricesTable->addForeignKeyConstraint('products', ['product_id'], ['id'], ['onDelete' => 'CASCADE']);
    }

    private function createGalleryTable(Schema $schema)
    {
        $galleryTable = $schema->createTable('gallery');
        $galleryTable->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $galleryTable->addColumn('product_id', Types::STRING, ['length' => 255]);
        $galleryTable->addColumn('image_url', Types::STRING, ['length' => 255]);
        $galleryTable->setPrimaryKey(['id']);

        $galleryTable->addForeignKeyConstraint('products', ['product_id'], ['id'], ['onDelete' => 'CASCADE']);
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
}
}
