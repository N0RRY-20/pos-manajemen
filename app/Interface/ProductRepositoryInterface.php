<?php

namespace App\Interface;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    /**
     * @param  array{search?: string, perPage?: int}  $filters
     */
    public function getProducts(array $filters = []): LengthAwarePaginator;

    public function storeProduct(array $data): Product;

    public function findProduct(int $id): ?Product;

    public function updateProduct(Product $product, array $data): bool;

    public function deleteProduct(Product $product): bool;

    public function getCategories(): Collection;
}
