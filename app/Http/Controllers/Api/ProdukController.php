<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // Ambil semua produk dengan kategori
    public function index()
    {
        $produks = Produk::with('kategori')->latest()->get();

        return response()->json([
            'message' => 'Daftar produk berhasil diambil',
            'data' => $produks
        ]);
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'stok'        => 'required|integer|min:0',
            'kategori_id' => 'required|integer|exists:kategoris,id',
        ]);

        // Generate kode_produk otomatis
        $lastId = Produk::max('id') ?? 0;
        $nextNumber = str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        $validated['kode_produk'] = 'PROD-' . date('Y') . '-' . $nextNumber;

        $produk = Produk::create($validated);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'data' => $produk
        ], 201);
    }

    // Update produk
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'stok'        => 'required|integer|min:0',
            'kategori_id' => 'required|integer|exists:kategoris,id',
        ]);

        $produk->update($validated);

        return response()->json([
            'message' => 'Produk berhasil diperbarui',
            'data' => $produk
        ]);
    }

    // Hapus produk (tidak dipanggil frontend)
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus'
        ]);
    }
}
