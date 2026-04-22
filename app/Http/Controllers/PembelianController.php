<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PembelianController extends Controller
{
    /**
     * Menampilkan riwayat pembelian customer yang sedang login.
     */
    public function index(Request $request): View
    {
        $pembelian = Pembelian::with(['produk.brand'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('pembelian.index', compact('pembelian'));
    }
}
