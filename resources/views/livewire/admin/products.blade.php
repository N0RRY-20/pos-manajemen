<div>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <flux:heading size="xl" level="1">Manajemen Produk</flux:heading>
            <flux:text class="mt-1 text-base">Kelola menu makanan, dimsum, minuman, stok, dan harga POS Anda</flux:text>
        </div>
        <!-- Button Tambah Produk (Akan disambungkan ke Action Create berikutnya) -->
        <flux:button icon="plus" variant="primary" class="self-start sm:self-auto cursor-pointer">
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
                                    <div class="w-10 h-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center overflow-hidden border border-zinc-200 dark:border-zinc-700 shrink-0">
                                        @if ($product->image_url)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
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
                                    <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $product->stock }}</span>
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
                                    <flux:button icon="pencil-square" size="sm" variant="ghost" tooltip="Edit Produk" />
                                    <flux:button icon="trash" size="sm" variant="ghost" class="text-red-500! hover:text-red-600!" tooltip="Hapus Produk" />
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
</div>
