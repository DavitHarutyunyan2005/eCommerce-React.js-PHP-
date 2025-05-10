<?php

namespace App\Factories;

use App\Models\Attribute\SwatchAttribute;
use App\Models\Attribute\TextAttribute;
/**
 * AttributeFactory is responsible for creating instances of different attribute types.
 */

class AttributeFactory
{
    public function create(array $data)
    {
        $attributeClass = $this->getAttributeClass($data['type']);
        return new $attributeClass($data['id'], $data['name'], $data['type'], $data['items']);
    }
    
    private function getAttributeClass(string $type): string
    {
        $attributeClasses = [
            'swatch' => SwatchAttribute::class,
            'text' => TextAttribute::class,
        ];

        if (!isset($attributeClasses[$type])) {
            throw new \InvalidArgumentException("Invalid attribute type: $type");
        }

        return $attributeClasses[$type];
    }
}
