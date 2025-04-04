<?php

namespace App\Models;


abstract class Attribute
{
    protected string $id;
    protected string $name;
    protected string $type;
    protected array $items = [];

    public function __construct( string $attribute_id, array $items = [])
    {
        $this->id = $attribute_id;
        $this->items = $items;
    }

    abstract public function formatValue($value): mixed;


    // Getters
    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
