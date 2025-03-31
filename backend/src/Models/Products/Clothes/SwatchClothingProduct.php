<?php

namespace Models\Products\Clothes;

use Models\Attribute\SwatchAttribute;
use Models\Category\ClothesCategory;
use Models\Product;

class SwatchClothingProduct extends Product
{
    protected string $id;
    protected string $name;
    protected string $description;
    protected ClothesCategory $category;
    protected SwatchAttribute $attribute;
    protected bool $inStock;
    protected array $gallery;
    protected float $amount;
    protected string $currency_label;
    protected string $currency_symbol;
    protected string $brand;

    public function __construct(
        string $id,
        string $name,
        bool $inStock,
        array $gallery,
        string $description,
        ClothesCategory $category,
        SwatchAttribute $attribute,
        float $amount,
        string $currency_label,
        string $currency_symbol,
        string $brand
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
            $brand
        );
        $this->category = $category;
        $this->attribute = $attribute;
    }


    // GETTERS:
    public function getCategory(): ClothesCategory
    {
        return $this->category;
    }

    public function getAttribute(): SwatchAttribute
    {
        return $this->attribute;
    }
}
