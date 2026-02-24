<?php

namespace App\Repositories;

use App\Interfaces\ProductInterface;

class ProductRepository implements ProductInterface
{
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function updateStock(int $id, int $quantityChange): bool
    {
        return Product::where('id', $id)->update([
            'stock' => \DB::raw("stock + $quantityChange")
        ]) > 0;
    }
}
