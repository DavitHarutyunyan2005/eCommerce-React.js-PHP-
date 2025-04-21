<?php

namespace App\Models\Product\Tech;

use App\Models\Attribute\SwatchAttribute;
use App\Models\Category\TechCategory;
use App\Models\Product;

class SwatchTechProduct extends Product
{
    protected string $id;
    protected string $name;
    protected string $description;
    protected TechCategory $category;
    protected SwatchAttribute $attribute;
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
        TechCategory $category,
        SwatchAttribute $attribute,
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
    public function getCategory(): TechCategory
    {
        return $this->category;
    }

    public function getAttribute(): SwatchAttribute
    {
        return $this->attribute;
    }
}
