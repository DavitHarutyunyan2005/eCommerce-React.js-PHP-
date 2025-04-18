<?php

namespace App\Repositories;

interface RepositoryInterface
{
    public function findAll(?string $id = null): array;
    public function save(array $data): void;
    public function delete(int $id): void;
}
