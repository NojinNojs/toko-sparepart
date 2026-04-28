<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Pembelian;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

/**
 * CheckoutController — menangani proses pembelian oleh customer.
 *
 * Ini adalah controller paling kritis dari sisi keamanan dan integritas data.
 * Menggunakan dua teknik penting:
 *   1. DB::transaction() → memastikan semua operasi berhasil atau gagal semuanya (atomicity)
 *   2. lockForUpdate()   → mencegah race condition saat 2 user checkout produk yang sama
 */
class CheckoutController extends Controller
{
    /**
     * Memproses pembelian produk oleh customer.
     *
     * Alur proses:
     *   1. Validasi input via CheckoutRequest (produk_id, jumlah)
     *   2. Mulai database transaction
     *   3. Lock baris produk agar tidak bisa dibaca user lain sampai selesai
     *   4. Cek apakah stok mencukupi
     *   5. Hitung total harga (harga × jumlah)
     *   6. Buat record pembelian dengan status 'pending'
     *   7. Kurangi stok produk
     *   8. Commit transaction & redirect ke halaman riwayat
     */
    public function store(CheckoutRequest $request): RedirectResponse
    {
        // Ambil data yang sudah divalidasi oleh CheckoutRequest
        $validated = $request->validated();

        try {
            // DB::transaction memastikan operasi di dalamnya bersifat ATOMIK.
            // Jika ada error di tengah (misalnya: database disconnect), semua perubahan
            // akan otomatis di-ROLLBACK sehingga data tidak setengah-setengah.
            return DB::transaction(function () use ($validated, $request): RedirectResponse {

                // lockForUpdate() → row-level lock di database.
                // Saat satu user sedang checkout, user lain yang mencoba checkout
                // produk SAMA akan 'menunggu' sampai transaksi ini selesai.
                // Ini mencegah "overselling" (stok berkurang lebih dari yang seharusnya).
                $produk = Produk::lockForUpdate()->findOrFail($validated['produk_id']);

                // Cek stok: jika tidak mencukupi, batalkan dan beri pesan error
                if ($produk->stok < $validated['jumlah']) {
                    return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: '.$produk->stok);
                }

                // Hitung total: snapshot harga saat ini (harga bisa berubah nanti)
                $total = $produk->harga * $validated['jumlah'];

                // Generate nomor invoice unik.
                // Format: INV-YYYYMMDD-XXXX (contoh: INV-20240428-A7K3)
                // Menggunakan random 4 karakter untuk menghindari duplicate
                // bahkan jika 2 transaksi terjadi di detik yang sama.
                $invoiceNo = 'INV-'.now()->format('Ymd').'-'.strtoupper(str()->random(4));

                // Simpan record pembelian ke database
                // Status awal selalu 'pending' — menunggu konfirmasi admin
                Pembelian::create([
                    'invoice_no' => $invoiceNo,
                    'user_id'    => $request->user()->id,
                    'produk_id'  => $produk->id,
                    'jumlah'     => $validated['jumlah'],
                    'total'      => $total,
                    'status'     => 'pending',
                ]);

                // Kurangi stok produk menggunakan decrement() — atomic operation di SQL:
                // UPDATE produk SET stok = stok - $jumlah WHERE id = ?
                $produk->decrement('stok', $validated['jumlah']);

                return redirect()
                    ->route('pembelian.index')
                    ->with('success', 'Pembelian berhasil! Menunggu konfirmasi admin. No. Invoice: '.$invoiceNo);
            });
        } catch (\Exception $e) {
            // Tangkap semua error tak terduga (misal: database down, invoice duplicate)
            // Jangan tampilkan pesan error teknis ke user — hanya pesan ramah
            return back()->with('error', 'Gagal memproses pembelian. Silakan coba lagi.');
        }
    }
}
