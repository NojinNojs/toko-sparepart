<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * HomeController — menangani halaman yang bisa diakses publik (tanpa login).
 *
 * Halaman: katalog produk (index) dan detail produk (show).
 * Tidak ada middleware auth di sini — siapapun boleh melihat katalog.
 */
class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama — katalog produk dengan filter dan pencarian.
     *
     * Mendukung 3 filter via URL query string:
     *   ?search=kampas   → cari berdasarkan nama produk
     *   ?kategori=2      → filter berdasarkan ID kategori
     *   ?brand=3         → filter berdasarkan ID brand
     *
     * Bisa dikombinasikan: ?search=oli&brand=1
     */
    public function index(Request $request): View
    {
        // Menggunakan method chaining dengan when() untuk membangun query secara kondisional.
        // when($kondisi, $callback) → hanya menambahkan WHERE clause jika $kondisi truthy.
        // Ini jauh lebih bersih daripada menulis if-elseif untuk setiap filter.
        $produk = Produk::with(['brand', 'kategori'])
            // Filter nama produk (LIKE search)
            ->when($request->search, fn ($q, $s) => $q->where('nama', 'like', "%{$s}%"))
            // Filter berdasarkan ID kategori
            ->when($request->kategori, fn ($q, $k) => $q->where('kategori_id', $k))
            // Filter berdasarkan ID brand
            ->when($request->brand, fn ($q, $b) => $q->where('brand_id', $b))
            ->latest()      // Urutkan dari yang terbaru (created_at DESC)
            ->paginate(12); // Tampilkan 12 produk per halaman

        // Ambil semua brand & kategori untuk ditampilkan di sidebar filter
        $brands    = Brand::orderBy('nama')->get();
        $kategoris = Kategori::orderBy('nama')->get();

        // compact() → shorthand untuk ['produk' => $produk, 'brands' => $brands, ...]
        return view('home', compact('produk', 'brands', 'kategoris'));
    }

    /**
     * Menampilkan halaman detail satu produk.
     *
     * Laravel secara otomatis mencari produk berdasarkan {produk} di URL
     * menggunakan Route Model Binding — tidak perlu Produk::findOrFail($id) manual.
     * Jika produk tidak ditemukan, Laravel otomatis return 404.
     */
    public function show(Produk $produk): View
    {
        // load() → lazy eager loading. Karena Produk sudah $with brand & kategori
        // di model, ini hanya memastikan data sudah ter-load (opsional tapi eksplisit).
        $produk->load(['brand', 'kategori']);

        return view('produk.show', compact('produk'));
    }
}
