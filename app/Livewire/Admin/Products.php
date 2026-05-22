<?php

namespace App\Livewire\Admin;

use App\Services\ProductService;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;


#[Layout('layouts.admin')]
#[Title('Products Admin')]
class Products extends Component
{
    use WithPagination;

    public string $search = '';

    protected ProductService $productService;

    public function boot(ProductService $productService): void
    {
        $this->productService = $productService;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $products = $this->productService->getDataProduct([
            'search' => $this->search,
        ]);

        return view('livewire.admin.products', compact('products'));
    }
}
