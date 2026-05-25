<?php

namespace App\Repositories;

use App\Interface\ProductRepositoryInterface;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @param  array{search?: string, perPage?: int}  $filters
     */
    public function getProducts(array $filters = []): LengthAwarePaginator
    {
        $search = $filters['search'] ?? '';
        $perPage = $filters['perPage'] ?? 10;

        return Product::with('category')->when(
            $search,
            fn ($query) => $query->where('name', 'like', "%{$search}%")
                ->orWhereHas('category', fn ($q) => $q->where('name', 'like', "%{$search}%"))
        )->latest()->paginate($perPage);
    }

    public function storeProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function findProduct(int $id): ?Product
    {
        return Product::find($id);
    }

    public function updateProduct(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    public function deleteProduct(Product $product): bool
    {
        return $product->delete();
    }

    public function getCategories(): Collection
    {
        return Category::orderBy('name', 'asc')->get();
    }
}
