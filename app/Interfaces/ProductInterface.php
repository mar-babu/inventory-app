<?php

namespace App\Interfaces;

interface ProductInterface
{
    public function create(array $data): Product;

    public function findById(int $id): ?Product;
    
    public function updateStock(int $id, int $quantityChange): bool;
}
