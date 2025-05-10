<?php

namespace App\Factories;

use App\Models\MadeFor\AbstractMadeFor;
use App\Models\MadeFor\KidsMadeFor;
use App\Models\MadeFor\MenMadeFor;
use App\Models\MadeFor\WomenMadeFor;
/**
 * MadeForFactory is responsible for creating instances of different made-for types.
 */


class MadeForFactory
{
    public function create(string $name): AbstractMadeFor
    {
        $madeForClass = $this->getMadeForClass($name);
        return new $madeForClass();
    } 
    private function getMadeForClass(string $name): string
    {
        $madeForClasses = [
            'Men' => MenMadeFor::class,
            'Women' => WomenMadeFor::class,
            'Kids' => KidsMadeFor::class,
        ];

        if (!isset($madeForClasses[$name])) {
            throw new \InvalidArgumentException("Invalid made for name: $name");
        }

        return $madeForClasses[$name];
    }
}
