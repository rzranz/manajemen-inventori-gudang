<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ✅ tambahkan ini

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with('produk')->latest()->get();

        return response()->json($transaksis);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'tipe'      => 'required|in:masuk,keluar',
            'jumlah'    => 'required|integer|min:1',
            'catatan'   => 'nullable|string',
        ]);

        try {
            DB::beginTransaction(); // ✅ pakai DB langsung

            $produk = Produk::findOrFail($validated['produk_id']);

            if ($validated['tipe'] === 'masuk') {
                $produk->stok += $validated['jumlah'];
            } else {
                if ($produk->stok < $validated['jumlah']) {
                    return response()->json([
                        'message' => 'Stok tidak mencukupi untuk transaksi keluar.'
                    ], 422);
                }
                $produk->stok -= $validated['jumlah'];
            }
            $produk->save();

            $transaksi = Transaksi::create($validated);

            DB::commit();

            return response()->json([
                'message'   => 'Transaksi berhasil disimpan.',
                'transaksi' => $transaksi->load('produk'),
                'produk'    => $produk,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
