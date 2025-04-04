<?php

namespace App\Models\Category;

use App\Models\Category;

class TechCategory extends Category
{

    public function __construct()
    {
        parent::__construct('Tech');
    }
}
