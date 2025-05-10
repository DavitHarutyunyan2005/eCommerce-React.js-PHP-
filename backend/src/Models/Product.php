<?php

namespace App\Models;

use App\Models\Category\AbstractCategory;
use App\Models\MadeFor\AbstractMadeFor;
use App\Models\Attribute\AbstractAttribute;
use App\Models\Price;

class Product
{
    private string $id;
    private string $name;
    private bool $inStock;
    /** @var string[] */
    private array $gallery;
    private string $description;
    private AbstractCategory $category;
    /** @var AbstractAttribute[] */
    private array $attributes = [];
    /** @var Price[] */
    private array $prices;
    private string $brand;
    private AbstractMadeFor $madeFor;

    public function __construct(
        string $id,
        string $name,
        bool $inStock,
        array $gallery,
        string $description,
        AbstractCategory $category,
        array $attributes,
        array $prices,
        string $brand,
        AbstractMadeFor $madeFor
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->inStock = $inStock;
        $this->gallery = $gallery;
        $this->description = $description;
        $this->category = $category;
        $this->attributes = $attributes;
        $this->prices = $prices;
        $this->brand = $brand;
        $this->madeFor = $madeFor;
    }

    // GETTERS:
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

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getGallery(): array
    {
        return $this->gallery;
    }

    public function getCategory(): AbstractCategory
    {
        return $this->category;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getPrices(): array
    {
        return $this->prices;
    }


    public function getMadeFor(): string
    {
        return $this->madeFor->getName();
    }
}
