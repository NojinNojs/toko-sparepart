<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\UpdateProdukRequest;
use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Produk;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * ProdukController — mengelola CRUD produk di panel admin.
 *
 * Menggunakan Dependency Injection untuk ImageService:
 * Laravel Service Container otomatis membuat instance ImageService dan
 * menyuntikkannya ke constructor — tidak perlu 'new ImageService()' manual.
 */
class ProdukController extends Controller
{
    /**
     * Constructor injection: ImageService di-inject otomatis oleh Laravel.
     * 'private readonly' → properti tidak bisa diubah setelah constructor (immutable).
     */
    public function __construct(
        private readonly ImageService $imageService
    ) {}

    /**
     * Menampilkan daftar semua produk untuk admin, dengan fitur pencarian.
     *
     * Pencarian dilakukan di 3 kolom: nama, kode, atau deskripsi.
     * Menggunakan paginate(15) → 15 produk per halaman.
     */
    public function index(Request $request): View
    {
        $produk = Produk::with(['brand', 'kategori'])
            // when(): hanya tambah WHERE jika ada parameter ?search=... di URL
            ->when($request->search, fn ($q, $s) => $q->where(function ($q) use ($s) {
                // Cari di 3 kolom sekaligus menggunakan orWhere
                $q->where('nama', 'like', "%{$s}%")
                    ->orWhere('kode', 'like', "%{$s}%")
                    ->orWhere('deskripsi', 'like', "%{$s}%");
            }))
            ->latest()
            ->paginate(15);

        return view('admin.produk.index', compact('produk'));
    }

    /**
     * Menampilkan form tambah produk baru.
     * Brand & kategori di-load untuk mengisi dropdown di form.
     */
    public function create(): View
    {
        $brands    = Brand::orderBy('nama')->get();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('admin.produk.create', compact('brands', 'kategoris'));
    }

    /**
     * Menyimpan produk baru ke database.
     *
     * Validasi sudah ditangani oleh StoreProdukRequest (termasuk cek authorize).
     * Dua sumber gambar yang didukung:
     *   1. Base64 (dari Cropper.js — gambar di-crop di browser)
     *   2. File upload biasa (tanpa crop)
     */
    public function store(StoreProdukRequest $request): RedirectResponse
    {
        // $request->validated() mengembalikan HANYA kolom yang ada di rules()
        // Aman digunakan untuk mass-assignment karena sudah terfilter
        $validated = $request->validated();

        // Cek sumber gambar: prioritaskan gambar hasil crop (base64)
        // karena lebih akurat dari sisi komposisi gambar
        if ($request->filled('cropped_image')) {
            // filled() → true jika field ada DAN tidak kosong (bukan null/empty string)
            $validated['gambar'] = $this->imageService->uploadBase64($request->input('cropped_image'));
        } elseif ($request->hasFile('gambar')) {
            // hasFile() → true jika ada file yang di-upload via input type="file"
            $validated['gambar'] = $this->imageService->upload($request->file('gambar'));
        }

        // Simpan produk baru — hanya kolom yang ada di $fillable yang akan tersimpan
        Produk::create($validated);

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail produk (admin view).
     * Route Model Binding: Laravel otomatis cari Produk berdasarkan {produk} di URL.
     */
    public function show(Produk $produk): View
    {
        // load() memastikan relasi brand & kategori ter-load
        // (meski sudah eager loaded di $with, ini eksplisit untuk kejelasan)
        $produk->load(['brand', 'kategori']);

        return view('admin.produk.show', compact('produk'));
    }

    /**
     * Menampilkan form edit produk yang sudah ada.
     */
    public function edit(Produk $produk): View
    {
        $brands    = Brand::orderBy('nama')->get();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('admin.produk.edit', compact('produk', 'brands', 'kategoris'));
    }

    /**
     * Memperbarui data produk di database.
     *
     * Jika ada gambar baru (via crop atau file upload):
     *   - Gambar baru diproses oleh ImageService (resize & compress)
     *   - Gambar lama dihapus otomatis dari storage
     * Jika tidak ada gambar baru, kolom 'gambar' tidak berubah.
     */
    public function update(UpdateProdukRequest $request, Produk $produk): RedirectResponse
    {
        $validated = $request->validated();

        // Proses gambar baru jika ada — lewatkan path gambar lama agar dihapus
        if ($request->filled('cropped_image')) {
            $validated['gambar'] = $this->imageService->uploadBase64(
                $request->input('cropped_image'),
                $produk->gambar // Path gambar lama → akan dihapus di dalam ImageService
            );
        } elseif ($request->hasFile('gambar')) {
            $validated['gambar'] = $this->imageService->upload(
                $request->file('gambar'),
                $produk->gambar // Path gambar lama → akan dihapus di dalam ImageService
            );
        }

        // Update kolom yang ada di $validated saja
        $produk->update($validated);

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk dari database.
     *
     * Dibungkus try-catch karena produk yang masih punya transaksi
     * tidak bisa dihapus (foreign key constraint dari tabel pembelian).
     */
    public function destroy(Produk $produk): RedirectResponse
    {
        try {
            // Hapus file gambar dari storage sebelum hapus record
            if ($produk->gambar) {
                $this->imageService->delete($produk->gambar);
            }

            $produk->delete();

            return redirect()
                ->route('admin.produk.index')
                ->with('success', 'Produk berhasil dihapus!');

        } catch (\Exception $e) {
            // Kemungkinan gagal karena: produk masih punya pembelian (FK constraint)
            // Tidak tampilkan pesan error teknis/raw SQL ke user
            return back()->with('error', 'Gagal menghapus produk. Produk mungkin masih memiliki transaksi terkait.');
        }
    }
}
