<?php

namespace App\Interface;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    /**
     * @param  array{search?: string, perPage?: int}  $filters
     */
    public function getProduct(array $filters = []): LengthAwarePaginator;
}
