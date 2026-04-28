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

/**
 * DashboardController — menampilkan ringkasan data seluruh aplikasi.
 *
 * Prinsip DRY (Don't Repeat Yourself) diterapkan di sini:
 * Baik index() maupun poll() menggunakan getDashboardData() yang sama,
 * sehingga tidak ada duplikasi logika pengambilan data.
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin (request pertama kali / full page load).
     * Data dikirim ke view via $data array yang di-unpack oleh view().
     */
    public function index(): View
    {
        $data = $this->getDashboardData();

        return view('admin.dashboard', $data);
    }

    /**
     * API endpoint untuk polling data dashboard secara realtime (AJAX).
     *
     * Dipanggil oleh Alpine.js setiap 15 detik via fetch('/admin/dashboard/poll').
     * Return JSON — data yang sama dengan index(), tapi diformat ulang karena
     * beberapa object Eloquent tidak bisa langsung di-JSON-kan (perlu di-map dulu).
     * Contoh: Carbon object (created_at) dikonversi ke string 'diffForHumans()' → "5 menit lalu".
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
     * Mengambil SEMUA data yang dibutuhkan dashboard dalam satu method.
     *
     * Dipanggil oleh index() → untuk render HTML
     * Dipanggil oleh poll() → untuk return JSON ke Alpine.js
     *
     * Return type: array — di-unpack oleh view() atau di-encode sebagai JSON.
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
        // Loop dari 6 hari lalu sampai hari ini (i=6 → i=0), menghasilkan array 7 elemen.
        // Carbon::today()->subDays($i) → objek tanggal, misal: subDays(2) = 2 hari lalu.
        // Setiap iterasi jalankan 1 query SUM → total 7 query (akses dashboard admin, wajar).
        $chartPendapatan = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $amount = Pembelian::where('status', 'dikonfirmasi')
                ->whereDate('created_at', $date) // Hanya transaksi di tanggal tersebut
                ->sum('total');                   // Jumlahkan kolom total
            $chartPendapatan->push([
                'label' => $date->format('d M'), // Format: "28 Apr"
                'value' => (int) $amount,         // Cast ke int (tidak perlu desimal di chart)
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
        // Query ini menggunakan LEFT JOIN + SUM + COALESCE:
        //
        // LEFT JOIN → ambil SEMUA produk, bahkan yang belum pernah dibeli
        //            (jika INNER JOIN, produk tanpa pembelian tidak akan muncul)
        //
        // COALESCE(SUM(...), 0) → jika produk tidak punya pembelian (NULL dari LEFT JOIN),
        //                         ganti NULL dengan 0 agar bisa diurutkan
        //
        // groupBy('produk.id') → wajib saat menggunakan aggregate function (SUM)
        //
        // SQL yang dihasilkan (kira-kira):
        //   SELECT produk.*, COALESCE(SUM(pembelian.jumlah), 0) as total_terjual
        //   FROM produk
        //   LEFT JOIN pembelian ON produk.id = pembelian.produk_id AND pembelian.status = 'dikonfirmasi'
        //   GROUP BY produk.id
        //   ORDER BY total_terjual DESC
        //   LIMIT 5
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
