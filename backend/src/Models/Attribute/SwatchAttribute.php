<?php

namespace App\Models\Attribute;


class SwatchAttribute extends AbstractAttribute
{
    protected string $type = 'swatch';

    public function formatValue($value): string
    {
        // Ensuring the value is a valid hex color code
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value)) {
            throw new \InvalidArgumentException("Invalid color value: $value");
        }

        return $value;  
    }
}
