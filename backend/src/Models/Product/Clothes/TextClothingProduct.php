<?php

namespace App\Models\Product\Clothes;

use App\Models\Attribute\TextAttribute;
use App\Models\Category\ClothesCategory;
use App\Models\Product;

class TextClothingProduct extends Product
{
    protected string $id;
    protected string $name;
    protected string $description;
    protected ClothesCategory $category;
    protected TextAttribute $attribute;
    protected bool $inStock;
    protected array $gallery;
    protected float $amount;
    protected string $currency_label;
    protected string $currency_symbol;
    protected string $brand;
    protected string $madeFor;

    public function __construct(
        string $id,
        string $name,
        bool $inStock,
        array $gallery,
        string $description,
        ClothesCategory $category,
        TextAttribute $attribute,
        float $amount,
        string $currency_label,
        string $currency_symbol,
        string $brand,
        string $madeFor
    ) {
        parent::__construct(
            $id, 
            $name, 
            $inStock, 
            $gallery, 
            $description,  
            $amount, 
            $currency_label, 
            $currency_symbol, 
            $brand,
            $madeFor
        );
        $this->category = $category;
        $this->attribute = $attribute;
    }


// GETTERS:
    public function getCategory(): ClothesCategory
    {
        return $this->category;
    }

    public function getAttribute(): TextAttribute
    {
        return $this->attribute;
    }
}