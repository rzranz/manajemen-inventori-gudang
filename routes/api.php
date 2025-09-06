<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\KategoriController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('kategoris', [KategoriController::class, 'index']);

// ✅ Produk (CRUD)
Route::apiResource('produks', ProdukController::class);

// ✅ Transaksi (CRUD)
Route::apiResource('transaksis', TransaksiController::class);
