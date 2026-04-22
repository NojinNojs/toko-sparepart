<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Pembelian;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Memproses pembelian produk oleh customer.
     *
     * Validasi stok, buat record pembelian, dan kurangi stok produk.
     * Menggunakan database transaction untuk menjaga integritas data.
     */
    public function store(CheckoutRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            return DB::transaction(function () use ($validated, $request): RedirectResponse {
                // Ambil produk dan lock row untuk mencegah race condition
                $produk = Produk::lockForUpdate()->findOrFail($validated['produk_id']);

                // Validasi stok mencukupi
                if ($produk->stok < $validated['jumlah']) {
                    return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: '.$produk->stok);
                }

                // Hitung total
                $total = $produk->harga * $validated['jumlah'];

                // Generate nomor invoice unik menggunakan random string untuk menghindari duplicate entry saat race condition
                $invoiceNo = 'INV-'.now()->format('Ymd').'-'.strtoupper(str()->random(4));

                // Buat record pembelian
                Pembelian::create([
                    'invoice_no' => $invoiceNo,
                    'user_id' => $request->user()->id,
                    'produk_id' => $produk->id,
                    'jumlah' => $validated['jumlah'],
                    'total' => $total,
                    'status' => 'pending',
                ]);

                // Kurangi stok produk
                $produk->decrement('stok', $validated['jumlah']);

                return redirect()
                    ->route('pembelian.index')
                    ->with('success', 'Pembelian berhasil! Menunggu konfirmasi admin. No. Invoice: '.$invoiceNo);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembelian. Silakan coba lagi.');
        }
    }
}
