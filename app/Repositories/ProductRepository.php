<?php

namespace App\Repositories;

use App\Interface\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @param  array{search?: string, perPage?: int}  $filters
     */
    public function getProduct(array $filters = []): LengthAwarePaginator
    {
        $search = $filters['search'] ?? '';
        $perPage = $filters['perPage'] ?? 10;

        return Product::with('category')->when(
            $search,
            fn($query) => $query->where('name', 'like', "%{$search}%")
        )->latest()->paginate($perPage);
    }
}
