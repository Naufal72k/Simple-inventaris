<?php

use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;


// Show the main page with the product list and form
Route::get('/', [ProdukController::class, 'index'])->name('produk.index');

// Store a new product
Route::post('/produk/store', [ProdukController::class, 'store'])->name('produk.store');

// Show the edit form for a specific product
Route::get('/produk/edit/{id}', [ProdukController::class, 'edit'])->name('produk.edit');

// Update a specific product
Route::post('/produk/update/{id}', [ProdukController::class, 'update'])->name('produk.update');

// Delete a specific product
Route::get('/produk/delete/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');
