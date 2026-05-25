<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;

class ProductForm extends Form
{
    public string $name = '';

    public string $price = '';

    public string $stock = '0';

    public string $category_id = '';

    public ?TemporaryUploadedFile $image_url = null;

    public bool $is_active = true;

    public ?int $productId = null;

    /**
     * Mengisi form dengan data produk yang sudah ada (untuk mode edit).
     */
    public function setProduct(Product $product): void
    {
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->price = (string) $product->price;
        $this->stock = (string) $product->stock;
        $this->category_id = (string) $product->category_id;
        $this->is_active = $product->is_active;
        $this->image_url = null;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                $this->productId ? 'unique:products,name,'.$this->productId : 'unique:products,name',
            ],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'image_url' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'is_active' => ['boolean'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'name' => 'product name',
            'price' => 'product price',
            'stock' => 'product stock',
            'category_id' => 'product category',
            'image_url' => 'product image ',
            'is_active' => 'product active status',
        ];
    }
}
