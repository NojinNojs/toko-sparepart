<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PembelianController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi untuk admin.
     *
     * Mendukung filter berdasarkan status.
     */
    public function index(Request $request): View
    {
        $pembelian = Pembelian::with(['user', 'produk.brand'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15);

        return view('admin.pembelian.index', compact('pembelian'));
    }

    /**
     * Menampilkan detail transaksi.
     */
    public function show(Pembelian $pembelian): View
    {
        $pembelian->load(['user', 'produk.brand', 'produk.kategori']);

        return view('admin.pembelian.show', compact('pembelian'));
    }

    /**
     * Memperbarui status transaksi (konfirmasi atau tolak).
     */
    public function updateStatus(Request $request, Pembelian $pembelian): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:dikonfirmasi,ditolak'],
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus dikonfirmasi atau ditolak.',
        ]);

        // Jika ditolak dan sebelumnya pending, kembalikan stok
        if ($validated['status'] === 'ditolak' && $pembelian->status === 'pending') {
            $pembelian->produk->increment('stok', $pembelian->jumlah);
        }

        $pembelian->update(['status' => $validated['status']]);

        $statusLabel = $validated['status'] === 'dikonfirmasi' ? 'dikonfirmasi' : 'ditolak';

        return redirect()
            ->route('admin.pembelian.index')
            ->with('success', "Transaksi {$pembelian->invoice_no} berhasil {$statusLabel}!");
    }
}
