<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\ProductForm;
use App\Services\ProductService;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Products Admin')]
class Products extends Component
{
    use WithFileUploads, WithPagination;

    public ProductForm $productForm;

    public string $search = '';

    public bool $isCreateModalOpen = false;

    public bool $isEditModalOpen = false;

    public ?int $productIdBeingEdited = null;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected ProductService $productService;

    public function boot(ProductService $productService): void
    {
        $this->productService = $productService;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->productForm->reset();
        $this->productIdBeingEdited = null;
        $this->isCreateModalOpen = true;
    }

    public function storeProduct(): void
    {
        $this->productForm->validate();

        $data = $this->productForm->except(['image_url']);
        $image = $this->productForm->image_url;

        $product = $this->productService->storeProduct($data, $image);

        if (! $product) {
            Flux::toast('Gagal menyimpan produk. Silakan coba lagi.', heading: 'Error', variant: 'danger');

            return;
        }

        $this->productForm->reset();
        $this->isCreateModalOpen = false;

        Flux::toast('Produk berhasil ditambahkan.', heading: 'Berhasil', variant: 'success');
    }

    /**
     * Membuka modal edit dan mengisi form dengan data produk.
     */
    public function openEditModal(int $id): void
    {
        $product = $this->productService->findProduct($id);

        if (! $product) {
            Flux::toast('Produk tidak ditemukan.', heading: 'Error', variant: 'danger');

            return;
        }

        $this->productIdBeingEdited = $product->id;
        $this->productForm->setProduct($product);
        $this->isEditModalOpen = true;
    }

    /**
     * Menyimpan perubahan produk yang sudah diedit.
     */
    public function updateProduct(): void
    {
        $this->productForm->validate();

        $data = $this->productForm->except(['image_url']);
        $image = $this->productForm->image_url;

        $success = $this->productService->updateProduct($this->productIdBeingEdited, $data, $image);

        if (! $success) {
            Flux::toast('Gagal mengupdate produk. Silakan coba lagi.', heading: 'Error', variant: 'danger');

            return;
        }

        $this->productForm->reset();
        $this->productIdBeingEdited = null;
        $this->isEditModalOpen = false;

        Flux::toast('Produk berhasil diperbarui.', heading: 'Berhasil', variant: 'success');
    }

    public function deleteProduct(int $id): void
    {
        $success = $this->productService->deleteProduct($id);

        if ($success) {
            Flux::toast('Produk berhasil dihapus.', heading: 'Berhasil', variant: 'success');
        } else {
            Flux::toast('Gagal menghapus produk.', heading: 'Error', variant: 'danger');
        }
    }

    public function render(): View
    {
        $products = $this->productService->getProducts([
            'search' => $this->search,
        ]);
        $categories = $this->productService->getCategories();

        return view('livewire.admin.products', compact('products', 'categories'));
    }
}
