<?php

namespace App\Models;

use App\Models\Attribute\SwatchAttribute;
use App\Models\Attribute\TextAttribute;
use App\Models\Category\ClothesCategory;
use App\Models\Category\TechCategory;

abstract class Product
{
    protected string $id;
    protected string $name;
    protected string $description;
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
        float $amount,
        string $currency_label,
        string $currency_symbol,
        string $brand
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->inStock = $inStock;
        $this->gallery = $gallery;
        $this->amount = $amount;
        $this->currency_label = $currency_label;
        $this->currency_symbol = $currency_symbol;
        $this->brand = $brand;
    }

    // GETTERS:
    abstract public function getCategory(): ClothesCategory|TechCategory;
    abstract public function getAttribute(): SwatchAttribute|TextAttribute;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isInStock(): bool
    {
        return $this->inStock;
    }

    public function getGallery(): array
    {
        return $this->gallery;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrencyLabel(): string
    {
        return $this->currency_label;
    }

    public function getCurrencySymbol(): string
    {
        return $this->currency_symbol;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }
}
