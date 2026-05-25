<?php

namespace App\Services;

use App\Interface\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private UploadService $uploadService
    ) {}

    /**
     * @param  array{search?: string, perPage?: int}  $filters
     */
    public function getProducts(array $filters = []): LengthAwarePaginator
    {
        try {
            return $this->productRepository->getProducts($filters);
        } catch (\Throwable $e) {
            report($e);

            return new Paginator([], 0, $filters['perPage'] ?? 10, 1);
        }
    }

    public function findProduct(int $id): ?Product
    {
        try {
            return $this->productRepository->findProduct($id);
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }

    public function storeProduct(array $data, ?UploadedFile $image = null): ?Product
    {
        $uploadedPath = null;

        try {
            return DB::transaction(function () use ($data, $image, &$uploadedPath) {
                if ($image) {
                    $uploadedPath = $this->uploadService->upload($image, 'products', $data['name']);
                    $data['image_url'] = $uploadedPath;
                }

                $data['slug'] = Str::slug($data['name']);

                return $this->productRepository->storeProduct($data);
            });
        } catch (\Throwable $e) {
            // Jika database transaksi gagal, hapus gambar yang baru saja terupload agar tidak jadi sampah
            if ($uploadedPath) {
                $this->uploadService->delete($uploadedPath);
            }

            report($e);

            return null;
        }
    }

    public function updateProduct(int $id, array $data, ?UploadedFile $image = null): bool
    {
        $oldImagePath = null;
        $uploadedPath = null;

        try {
            return DB::transaction(function () use ($id, $data, $image, &$oldImagePath, &$uploadedPath) {
                $product = $this->productRepository->findProduct($id);
                if (! $product) {
                    return false;
                }

                if ($image) {
                    $oldImagePath = $product->image_url;
                    $uploadedPath = $this->uploadService->upload($image, 'products', $data['name']);
                    $data['image_url'] = $uploadedPath;
                }

                $data['slug'] = Str::slug($data['name']);

                $updated = $this->productRepository->updateProduct($product, $data);

                // Jika update sukses di database dan ada gambar lama, hapus gambar lama tersebut
                if ($updated && $image && $oldImagePath) {
                    $this->uploadService->delete($oldImagePath);
                }

                return $updated;
            });
        } catch (\Throwable $e) {
            // Jika transaksi database gagal, bersihkan gambar baru yang terlanjur terupload
            if ($uploadedPath) {
                $this->uploadService->delete($uploadedPath);
            }

            report($e);

            return false;
        }
    }

    public function deleteProduct(int $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $product = $this->productRepository->findProduct($id);
                if (! $product) {
                    return false;
                }

                $imageUrl = $product->image_url;

                // Hapus data dari database terlebih dahulu
                $deleted = $this->productRepository->deleteProduct($product);

                // Jika data berhasil terhapus dari DB, hapus file gambarnya
                if ($deleted && $imageUrl) {
                    $this->uploadService->delete($imageUrl);
                }

                return $deleted;
            });
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }

    public function getCategories(): Collection
    {
        try {
            return $this->productRepository->getCategories();
        } catch (\Throwable $e) {
            report($e);

            return Collection::empty();
        }
    }
}
