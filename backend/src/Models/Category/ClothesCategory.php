<?php

namespace App\Models\Category;

class ClothesCategory extends AbstractCategory {
    public function __construct() {
        parent::__construct('clothes');
    }
}