<?php

namespace App\Models\Category;

use App\Models\Category;

class ClothesCategory extends Category {

    public function __construct() {
        parent::__construct('Clothes');
    }
}