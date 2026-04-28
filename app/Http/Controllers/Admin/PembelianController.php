<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * PembelianController (Admin) — mengelola daftar dan konfirmasi transaksi.
 *
 * Admin bisa:
 *   - Melihat semua transaksi (dengan filter status)
 *   - Melihat detail satu transaksi
 *   - Mengubah status: pending → dikonfirmasi / ditolak
 *
 * Jika transaksi DITOLAK, stok produk dikembalikan secara otomatis.
 */
class PembelianController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi untuk admin.
     *
     * Filter via URL: ?status=pending | ?status=dikonfirmasi | ?status=ditolak
     * Eager load 'produk.brand' menggunakan dot notation untuk load nested relation:
     *   pembelian → produk → brand (3 tabel dalam 1 query)
     */
    public function index(Request $request): View
    {
        $pembelian = Pembelian::with(['user', 'produk.brand'])
            // Filter status hanya jika ada parameter ?status= di URL
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15);

        return view('admin.pembelian.index', compact('pembelian'));
    }

    /**
     * Menampilkan detail satu transaksi.
     * Eager load nested: produk.brand dan produk.kategori
     */
    public function show(Pembelian $pembelian): View
    {
        // Load lebih lengkap untuk halaman detail: brand dan kategori produk
        $pembelian->load(['user', 'produk.brand', 'produk.kategori']);

        return view('admin.pembelian.show', compact('pembelian'));
    }

    /**
     * Memperbarui status transaksi menjadi 'dikonfirmasi' atau 'ditolak'.
     *
     * Logika penting:
     *   - Jika DITOLAK dan status SEBELUMNYA adalah 'pending' → kembalikan stok
     *   - Kita tidak mengembalikan stok jika transaksi sebelumnya sudah 'dikonfirmasi'
     *     (kasus ini seharusnya tidak terjadi karena tombol hanya muncul saat pending,
     *      tapi cek ini melindungi dari manipulasi request langsung)
     */
    public function updateStatus(Request $request, Pembelian $pembelian): RedirectResponse
    {
        // Validasi inline (bukan FormRequest karena hanya 1 field sederhana)
        $validated = $request->validate([
            'status' => ['required', 'in:dikonfirmasi,ditolak'],
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in'       => 'Status harus dikonfirmasi atau ditolak.',
        ]);

        // Kembalikan stok HANYA jika:
        //   1. Status baru = 'ditolak' (bukan dikonfirmasi)
        //   2. Status lama = 'pending' (stok memang sudah dikurangi saat checkout)
        // increment() → atomic: UPDATE produk SET stok = stok + $jumlah WHERE id = ?
        if ($validated['status'] === 'ditolak' && $pembelian->status === 'pending') {
            $pembelian->produk->increment('stok', $pembelian->jumlah);
        }

        // Update status di database
        $pembelian->update(['status' => $validated['status']]);

        // Label user-friendly untuk flash message
        $statusLabel = $validated['status'] === 'dikonfirmasi' ? 'dikonfirmasi' : 'ditolak';

        return redirect()
            ->route('admin.pembelian.index')
            ->with('success', "Transaksi {$pembelian->invoice_no} berhasil {$statusLabel}!");
    }
}
