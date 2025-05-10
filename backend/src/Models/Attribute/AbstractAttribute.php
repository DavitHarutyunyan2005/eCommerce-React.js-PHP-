<?php

namespace App\Models\Attribute;


abstract class AbstractAttribute
{
    private string $id;
    private string $name;
    private string $type;

    /** @var AttributeItem[] */
    private array $items = [];

    public function __construct( string $id, string $name, string $type, array $items = [] )
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type; 
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
