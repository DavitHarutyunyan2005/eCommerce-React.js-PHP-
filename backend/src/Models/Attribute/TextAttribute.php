<?php

namespace App\Models\Attribute;

use App\Models\Attribute;

class TextAttribute extends Attribute {

    protected string $type = 'text';
    protected string $name = 'Size';

    public function formatValue($value): string
    {
        return (string) $value;
    }
}