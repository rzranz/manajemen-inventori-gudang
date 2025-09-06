<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;

class KategoriController extends Controller
{
    // Ambil semua kategori
    public function index()
    {
        $kategoris = Kategori::all();

        return response()->json([
            'message' => 'Daftar kategori berhasil diambil',
            'data' => $kategoris
        ]);
    }
}
