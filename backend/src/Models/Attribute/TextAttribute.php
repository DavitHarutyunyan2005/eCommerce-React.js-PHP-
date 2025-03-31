<?php

namespace Models\Attribute;

use Models\Attribute;

class TextAttribute extends Attribute {

    protected string $type = 'text';
    protected string $name = 'Size';

    public function formatValue($value): string
    {
        return (string) $value;
    }
}