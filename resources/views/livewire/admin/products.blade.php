<div>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <flux:heading size="xl" level="1">Manajemen Produk</flux:heading>
            <flux:text class="mt-1 text-base">Kelola menu makanan, dimsum, minuman, stok, dan harga POS Anda</flux:text>
        </div>
        <flux:button wire:click="openCreateModal" variant="primary" icon="plus"
            class="self-start sm:self-auto cursor-pointer">
            Tambah Produk
        </flux:button>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <!-- Pencarian & Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
        <div class="w-full sm:w-96">
            <flux:input type="text" placeholder="Cari nama produk atau kategori..." icon="magnifying-glass"
                wire:model.live.debounce.300ms="search" />
        </div>
    </div>

    <!-- Container Table dengan Desain Premium -->
    <div
        class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden shadow-xs">
        @if ($products->isEmpty())
            <!-- State Kosong / Tidak Ditemukan -->
            <div class="flex flex-col items-center justify-center p-12 text-center">
                <flux:icon name="archive-box" class="w-16 h-16 text-zinc-400 dark:text-zinc-600 mb-4" />
                <flux:heading size="lg" class="mb-1 text-zinc-800 dark:text-zinc-200">Produk tidak ditemukan
                </flux:heading>
                <flux:text class="max-w-md">
                    Tidak ada produk yang cocok dengan pencarian "{{ $search }}". Silakan periksa kembali kata
                    kunci Anda.
                </flux:text>
            </div>
        @else
            <!-- Table List Produk -->
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="ps-6!">Menu / Produk</flux:table.column>
                    <flux:table.column>Kategori</flux:table.column>
                    <flux:table.column align="end">Harga</flux:table.column>
                    <flux:table.column align="center">Stok</flux:table.column>
                    <flux:table.column align="center">Status</flux:table.column>
                    <flux:table.column align="end" class="pe-6!">Aksi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($products as $product)
                        <flux:table.row :key="$product->id">

                            {{-- Produk --}}
                            <flux:table.cell variant="strong" class="ps-6!">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center overflow-hidden border border-zinc-200 dark:border-zinc-700 shrink-0">
                                        @if ($product->image_url)
                                            <img src="{{ asset('storage/' . $product->image_url) }}"
                                                alt="{{ $product->name }}" class="w-full h-full object-cover" />
                                        @else
                                            <flux:icon name="photo" class="w-5 h-5 text-zinc-400" />
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold truncate">{{ $product->name }}</div>
                                        <div class="text-xs text-zinc-500 truncate mt-0.5">{{ $product->slug }}</div>
                                    </div>
                                </div>
                            </flux:table.cell>

                            {{-- Kategori --}}
                            <flux:table.cell>
                                <flux:badge color="zinc" size="sm" class="capitalize">
                                    {{ $product->category?->name ?? 'Tanpa Kategori' }}
                                </flux:badge>
                            </flux:table.cell>

                            {{-- Harga --}}
                            <flux:table.cell variant="strong" align="end">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </flux:table.cell>

                            {{-- Stok --}}
                            <flux:table.cell align="center">
                                @if ($product->stock <= 5)
                                    <flux:badge color="red" size="sm">{{ $product->stock }} Menipis</flux:badge>
                                @else
                                    <span
                                        class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $product->stock }}</span>
                                @endif
                            </flux:table.cell>

                            {{-- Status --}}
                            <flux:table.cell align="center">
                                <flux:badge :color="$product->is_active ? 'green' : 'red'" size="sm">
                                    {{ $product->is_active ? 'Aktif' : 'Non-aktif' }}
                                </flux:badge>
                            </flux:table.cell>

                            {{-- Aksi --}}
                            <flux:table.cell align="end" class="pe-6!">
                                <div class="inline-flex items-center gap-1">
                                    <flux:button icon="pencil-square" size="sm" variant="ghost"
                                        wire:click="openEditModal({{ $product->id }})"
                                        tooltip="Edit Produk" />
                                    <flux:modal.trigger name="delete-product-{{ $product->id }}">
                                        <flux:button icon="trash" size="sm" variant="ghost"
                                            class="text-red-500! hover:text-red-600!" tooltip="Hapus Produk" />
                                    </flux:modal.trigger>

                                    <flux:modal name="delete-product-{{ $product->id }}" class="min-w-88">
                                        <div class="space-y-6 text-start">
                                            <div>
                                                <flux:heading size="lg">Hapus Produk?</flux:heading>

                                                <flux:text class="mt-2">
                                                    Anda yakin ingin menghapus produk
                                                    <strong>{{ $product->name }}</strong>?<br>
                                                    Tindakan ini tidak dapat dibatalkan.
                                                </flux:text>
                                            </div>

                                            <div class="flex gap-2">
                                                <flux:spacer />

                                                <flux:modal.close>
                                                    <flux:button variant="ghost">Batal</flux:button>
                                                </flux:modal.close>

                                                <flux:button variant="danger"
                                                    wire:click="deleteProduct({{ $product->id }})"
                                                    x-on:click="$flux.modal('delete-product-{{ $product->id }}').close()">
                                                    Hapus Produk</flux:button>
                                            </div>
                                        </div>
                                    </flux:modal>
                                </div>
                            </flux:table.cell>

                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>





            <!-- Bagian Pagination di Footer Table -->
            <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-900/20">
                {{ $products->links() }}
            </div>
        @endif
    </div>


    {{-- ================================================================================= --}}

    <flux:modal wire:model="isCreateModalOpen" class="min-w-[24rem] max-w-lg">
        <form wire:submit="storeProduct" class="space-y-6">
            <div>
                <flux:heading size="lg">Tambah Produk Baru</flux:heading>
                <flux:subheading>Isi formulir berikut untuk menambahkan item menu baru ke POS Dimsum.</flux:subheading>
            </div>
            <!-- Input Nama -->
            <flux:field>
                <flux:label>Nama Produk</flux:label>
                <flux:input wire:model="productForm.name" placeholder="mis. Siomay Udang Keju" />
                <flux:error name="productForm.name" />
            </flux:field>
            <!-- Pilihan Kategori -->
            <flux:field>
                <flux:label>Kategori</flux:label>
                <flux:select wire:model="productForm.category_id" placeholder="Pilih Kategori...">
                    @foreach ($categories as $category)
                        <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="productForm.category_id" />
            </flux:field>
            <div class="grid grid-cols-2 gap-4">
                <!-- Input Harga -->
                <flux:field>
                    <flux:label>Harga (Rp)</flux:label>
                    <flux:input type="number" wire:model="productForm.price" placeholder="22000" />
                    <flux:error name="productForm.price" />
                </flux:field>
                <!-- Input Stok -->
                <flux:field>
                    <flux:label>Stok Awal</flux:label>
                    <flux:input type="number" wire:model="productForm.stock" placeholder="50" />
                    <flux:error name="productForm.stock" />
                </flux:field>
            </div>
            <!-- Input Image URL -->
            <flux:field>
                <div class="space-y-4">

                    {{-- Upload Area --}}
                    <label for="image"
                        class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-zinc-300 bg-zinc-50 px-6 py-10 text-center transition hover:border-zinc-400 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-zinc-600 dark:hover:bg-zinc-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mb-3 h-10 w-10 text-zinc-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 15a4 4 0 014-4h1m4 0h5a4 4 0 010 8H7a4 4 0 01-4-4zm9-8v8m0 0l-3-3m3 3l3-3" />
                        </svg>

                        <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200">
                            Drop files here or click to browse
                        </p>

                        <p class="mt-1 text-xs text-zinc-500">
                            JPG, PNG, WEBP up to 2MB
                        </p>

                        <input id="image" type="file" wire:model="productForm.image_url" class="hidden">
                    </label>

                    {{-- Error --}}
                    @error('productForm.image_url')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror

                    {{-- Preview --}}
                    @if ($productForm->image_url)
                        <div
                            class="flex items-center gap-3 rounded-xl border border-zinc-200 bg-white p-3 dark:border-zinc-700 dark:bg-zinc-900">
                            <img src="{{ $productForm->image_url->temporaryUrl() }}"
                                class="h-16 w-16 rounded-lg object-cover">

                            <div class="flex-1">
                                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200">
                                    {{ $productForm->image_url->getClientOriginalName() }}
                                </p>

                                <p class="text-xs text-zinc-500">
                                    {{ round($productForm->image_url->getSize() / 1024, 1) }} KB
                                </p>
                            </div>

                            <button type="button" wire:click="$set('productForm.image_url', null)"
                                class="rounded-lg px-3 py-1 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">
                                Remove
                            </button>
                        </div>
                    @endif

                </div>
            </flux:field>
            <!-- Switch Status Aktif -->
            <flux:field class="flex items-center justify-between">
                <div>
                    <flux:label>Status Produk</flux:label>
                    <flux:description>Apakah menu ini langsung tampil di POS & bisa dipesan?</flux:description>
                </div>
                <flux:switch wire:model="productForm.is_active" />
            </flux:field>
            <!-- Aksi Form -->
            <div class="flex space-x-2 justify-end">
                <flux:button wire:click="$set('isCreateModalOpen', false)" variant="ghost">Batal</flux:button>
                <flux:button type="submit" variant="primary">Simpan Produk</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- ================================================================================= --}}

    <!-- Modal Edit Produk -->
    <flux:modal wire:model="isEditModalOpen" class="min-w-[24rem] max-w-lg">
        <form wire:submit="updateProduct" class="space-y-6">
            <div>
                <flux:heading size="lg">Edit Produk</flux:heading>
                <flux:subheading>Perbarui informasi produk yang sudah ada.</flux:subheading>
            </div>
            <!-- Input Nama -->
            <flux:field>
                <flux:label>Nama Produk</flux:label>
                <flux:input wire:model="productForm.name" placeholder="mis. Siomay Udang Keju" />
                <flux:error name="productForm.name" />
            </flux:field>
            <!-- Pilihan Kategori -->
            <flux:field>
                <flux:label>Kategori</flux:label>
                <flux:select wire:model="productForm.category_id" placeholder="Pilih Kategori...">
                    @foreach ($categories as $category)
                        <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="productForm.category_id" />
            </flux:field>
            <div class="grid grid-cols-2 gap-4">
                <!-- Input Harga -->
                <flux:field>
                    <flux:label>Harga (Rp)</flux:label>
                    <flux:input type="number" wire:model="productForm.price" placeholder="22000" />
                    <flux:error name="productForm.price" />
                </flux:field>
                <!-- Input Stok -->
                <flux:field>
                    <flux:label>Stok</flux:label>
                    <flux:input type="number" wire:model="productForm.stock" placeholder="50" />
                    <flux:error name="productForm.stock" />
                </flux:field>
            </div>
            <!-- Input Image -->
            <flux:field>
                <div class="space-y-4">

                    {{-- Upload Area --}}
                    <label for="edit-image"
                        class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-zinc-300 bg-zinc-50 px-6 py-10 text-center transition hover:border-zinc-400 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-zinc-600 dark:hover:bg-zinc-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mb-3 h-10 w-10 text-zinc-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 15a4 4 0 014-4h1m4 0h5a4 4 0 010 8H7a4 4 0 01-4-4zm9-8v8m0 0l-3-3m3 3l3-3" />
                        </svg>

                        <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200">
                            Ganti gambar (opsional)
                        </p>

                        <p class="mt-1 text-xs text-zinc-500">
                            JPG, PNG, WEBP up to 2MB
                        </p>

                        <input id="edit-image" type="file" wire:model="productForm.image_url" class="hidden">
                    </label>

                    {{-- Error --}}
                    @error('productForm.image_url')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror

                    {{-- Preview gambar baru --}}
                    @if ($productForm->image_url)
                        <div
                            class="flex items-center gap-3 rounded-xl border border-zinc-200 bg-white p-3 dark:border-zinc-700 dark:bg-zinc-900">
                            <img src="{{ $productForm->image_url->temporaryUrl() }}"
                                class="h-16 w-16 rounded-lg object-cover">

                            <div class="flex-1">
                                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200">
                                    {{ $productForm->image_url->getClientOriginalName() }}
                                </p>

                                <p class="text-xs text-zinc-500">
                                    {{ round($productForm->image_url->getSize() / 1024, 1) }} KB
                                </p>
                            </div>

                            <button type="button" wire:click="$set('productForm.image_url', null)"
                                class="rounded-lg px-3 py-1 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">
                                Remove
                            </button>
                        </div>
                    @endif

                </div>
            </flux:field>
            <!-- Switch Status Aktif -->
            <flux:field class="flex items-center justify-between">
                <div>
                    <flux:label>Status Produk</flux:label>
                    <flux:description>Apakah menu ini langsung tampil di POS & bisa dipesan?</flux:description>
                </div>
                <flux:switch wire:model="productForm.is_active" />
            </flux:field>
            <!-- Aksi Form -->
            <div class="flex space-x-2 justify-end">
                <flux:button wire:click="$set('isEditModalOpen', false)" variant="ghost">Batal</flux:button>
                <flux:button type="submit" variant="primary">Update Produk</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
