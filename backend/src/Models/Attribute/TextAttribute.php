<?php

namespace App\Models\Attribute;

class TextAttribute extends AbstractAttribute {

    protected string $type = 'text';

    public function formatValue($value): string
    {
        return (string) $value;
    }
}