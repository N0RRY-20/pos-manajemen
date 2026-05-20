<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});


Route::middleware(['auth', 'can:admin-only'])->group(function () {
    Route::get('admin/dashboard', Dashboard::class)->name('admin.dashboard');
});
