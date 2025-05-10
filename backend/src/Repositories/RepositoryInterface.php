<?php

namespace App\Repositories;

interface RepositoryInterface
{
    public function findAll(?string $id = null): array;

}
