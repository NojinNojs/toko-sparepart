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

class ProdukController extends Controller
{
    public function __construct(
        private readonly ImageService $imageService
    ) {}

    /**
     * Menampilkan daftar semua produk untuk admin.
     */
    public function index(Request $request): View
    {
        $produk = Produk::with(['brand', 'kategori'])
            ->when($request->search, fn ($q, $s) => $q->where(function ($q) use ($s) {
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
     */
    public function create(): View
    {
        $brands = Brand::orderBy('nama')->get();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('admin.produk.create', compact('brands', 'kategoris'));
    }

    /**
     * Menyimpan produk baru ke database.
     * Gambar akan di-resize dan dioptimasi oleh ImageService.
     */
    public function store(StoreProdukRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Prioritaskan gambar hasil crop (Base64), fallback ke file biasa
        if ($request->filled('cropped_image')) {
            $validated['gambar'] = $this->imageService->uploadBase64($request->input('cropped_image'));
        } elseif ($request->hasFile('gambar')) {
            $validated['gambar'] = $this->imageService->upload($request->file('gambar'));
        }

        Produk::create($validated);

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail produk (admin view).
     */
    public function show(Produk $produk): View
    {
        $produk->load(['brand', 'kategori']);

        return view('admin.produk.show', compact('produk'));
    }

    /**
     * Menampilkan form edit produk.
     */
    public function edit(Produk $produk): View
    {
        $brands = Brand::orderBy('nama')->get();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('admin.produk.edit', compact('produk', 'brands', 'kategoris'));
    }

    /**
     * Memperbarui data produk di database.
     * Gambar baru akan di-resize dan gambar lama dihapus otomatis.
     */
    public function update(UpdateProdukRequest $request, Produk $produk): RedirectResponse
    {
        $validated = $request->validated();

        // Upload gambar baru (otomatis hapus gambar lama jika ada)
        // Cek input base64 dari cropper lebih dulu
        if ($request->filled('cropped_image')) {
            $validated['gambar'] = $this->imageService->uploadBase64(
                $request->input('cropped_image'),
                $produk->gambar // old path untuk dihapus
            );
        } elseif ($request->hasFile('gambar')) {
            $validated['gambar'] = $this->imageService->upload(
                $request->file('gambar'),
                $produk->gambar // old path untuk dihapus
            );
        }

        $produk->update($validated);

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk dari database.
     */
    public function destroy(Produk $produk): RedirectResponse
    {
        try {
            // Hapus gambar jika ada
            if ($produk->gambar) {
                $this->imageService->delete($produk->gambar);
            }

            $produk->delete();

            return redirect()
                ->route('admin.produk.index')
                ->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus produk. Produk mungkin masih memiliki transaksi terkait.');
        }
    }
}
