<?php

namespace App\Models\Attribute;

use App\Models\Attribute;

class SwatchAttribute extends Attribute
{
    protected string $type = 'swatch';
    protected string $name = 'Color';

    public function formatValue($value): string
    {
        // Ensuring the value is a valid hex color code
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value)) {
            throw new \InvalidArgumentException("Invalid color value: $value");
        }

        return $value;  
    }
}
