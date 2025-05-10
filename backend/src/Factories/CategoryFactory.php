<?php

namespace App\Factories;

use App\Models\Category\AbstractCategory;
use App\Models\Category\ClothesCategory;
use App\Models\Category\TechCategory;
/**
 * CategoryFactory is responsible for creating instances of different category types.
 */

class CategoryFactory
{
    public function create(string $name): AbstractCategory
    {
        $categoryClass = $this->getCategoryClass($name);
        return new $categoryClass();
    }
    
    private function getCategoryClass(string $name): string
    {
        $categoryClasses = [
            'clothes' => ClothesCategory::class,
            'tech' => TechCategory::class,
        ];

        if (!isset($categoryClasses[$name])) {
            throw new \InvalidArgumentException("Invalid category name: $name");
        }

        return $categoryClasses[$name];
    }
}
