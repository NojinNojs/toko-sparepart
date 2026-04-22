<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin dengan ringkasan statistik lengkap.
     */
    public function index(): View
    {
        $data = $this->getDashboardData();

        return view('admin.dashboard', $data);
    }

    /**
     * API endpoint untuk polling data dashboard secara realtime.
     * Dipanggil oleh Alpine.js setiap 15 detik.
     */
    public function poll(): JsonResponse
    {
        $data = $this->getDashboardData();

        return response()->json([
            'stats' => $data['stats'],
            'chartPendapatan' => $data['chartPendapatan'],
            'chartStatus' => $data['chartStatus'],
            'transaksiTerbaru' => $data['transaksiTerbaru']->map(fn ($t) => [
                'invoice_no' => $t->invoice_no,
                'user_name' => $t->user->name ?? '-',
                'produk_nama' => $t->produk->nama ?? '-',
                'jumlah' => $t->jumlah,
                'total' => number_format($t->total, 0, ',', '.'),
                'status' => $t->status,
                'tanggal' => $t->created_at->diffForHumans(),
            ]),
            'stokRendah' => $data['stokRendah']->map(fn ($p) => [
                'id' => $p->id,
                'nama' => $p->nama,
                'kode' => $p->kode,
                'stok' => $p->stok,
                'gambar' => $p->gambar ? asset('storage/'.$p->gambar) : null,
                'brand' => $p->brand->nama ?? '-',
                'edit_url' => route('admin.produk.edit', $p),
            ]),
            'produkTerlaris' => $data['produkTerlaris']->map(fn ($p) => [
                'nama' => $p->nama,
                'total_terjual' => $p->total_terjual,
                'gambar' => $p->gambar ? asset('storage/'.$p->gambar) : null,
            ]),
            'timestamp' => now()->format('H:i:s'),
        ]);
    }

    /**
     * Mengambil semua data yang dibutuhkan dashboard.
     */
    private function getDashboardData(): array
    {
        // --- Statistik Utama ---
        $stats = [
            'total_produk' => Produk::count(),
            'total_transaksi' => Pembelian::count(),
            'pendapatan' => Pembelian::where('status', 'dikonfirmasi')->sum('total'),
            'pesanan_pending' => Pembelian::where('status', 'pending')->count(),
            'total_customer' => User::where('role', 'customer')->count(),
            'total_brand' => Brand::count(),
            'total_kategori' => Kategori::count(),
            'pesanan_ditolak' => Pembelian::where('status', 'ditolak')->count(),
            'pesanan_dikonfirmasi' => Pembelian::where('status', 'dikonfirmasi')->count(),
        ];

        // --- Chart: Pendapatan 7 Hari Terakhir ---
        $chartPendapatan = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $amount = Pembelian::where('status', 'dikonfirmasi')
                ->whereDate('created_at', $date)
                ->sum('total');
            $chartPendapatan->push([
                'label' => $date->format('d M'),
                'value' => (int) $amount,
            ]);
        }

        // --- Chart: Status Transaksi (Donut) ---
        $chartStatus = [
            ['label' => 'Menunggu',      'value' => $stats['pesanan_pending'], 'color' => '#eab308'],
            ['label' => 'Dikonfirmasi',  'value' => $stats['pesanan_dikonfirmasi'], 'color' => '#22c55e'],
            ['label' => 'Ditolak',       'value' => $stats['pesanan_ditolak'], 'color' => '#ef4444'],
        ];

        // --- 5 Transaksi Terbaru ---
        $transaksiTerbaru = Pembelian::with(['user', 'produk'])
            ->latest()
            ->take(5)
            ->get();

        // --- Produk Stok Rendah (< 10) ---
        $stokRendah = Produk::with('brand')
            ->where('stok', '<=', 10)
            ->orderBy('stok', 'asc')
            ->take(5)
            ->get();

        // --- Produk Terlaris ---
        $produkTerlaris = Produk::select('produk.*')
            ->selectRaw('COALESCE(SUM(pembelian.jumlah), 0) as total_terjual')
            ->leftJoin('pembelian', function ($join) {
                $join->on('produk.id', '=', 'pembelian.produk_id')
                    ->where('pembelian.status', '=', 'dikonfirmasi');
            })
            ->groupBy('produk.id')
            ->orderByDesc('total_terjual')
            ->take(5)
            ->get();

        return compact(
            'stats',
            'chartPendapatan',
            'chartStatus',
            'transaksiTerbaru',
            'stokRendah',
            'produkTerlaris'
        );
    }
}
