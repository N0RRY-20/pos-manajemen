<?php

namespace App\Services;

use App\Interface\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private ProductRepositoryInterface $productRepository) {}

    /**
     * @param  array{search?: string, perPage?: int}  $filters
     */
    public function getDataProduct(array $filters = []): LengthAwarePaginator
    {
        try {
            return $this->productRepository->getProduct($filters);
        } catch (\Exception $e) {
            // Handle the exception, log it, or rethrow it as needed
            throw new \RuntimeException('Failed to retrieve products: '.$e->getMessage(), 0, $e);
        }
    }
}
