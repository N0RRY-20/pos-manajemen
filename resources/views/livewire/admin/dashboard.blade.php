<div>
    <!-- Judul & Deskripsi Halaman Spesifik Dashboard -->
    <flux:heading size="xl" level="1">Selamat Datang, {{ auth()->user()->name }}</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">Berikut adalah ringkasan bisnis Anda hari ini</flux:text>
    <flux:separator variant="subtle" />

    <!-- Konten Dashboard Anda lainnya ditaruh di bawah sini (grafik, statistik ringkas, dll.) -->
    <div class="mt-6">
        <p class="text-zinc-600 dark:text-zinc-400">Konten statistik dashboard admin akan tampil di sini...</p>
    </div>
</div>
