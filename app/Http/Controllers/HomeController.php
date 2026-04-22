<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (katalog produk) dengan filter dan pencarian.
     */
    public function index(Request $request): View
    {
        // Ambil produk dengan eager loading brand & kategori
        $produk = Produk::with(['brand', 'kategori'])
            ->when($request->search, fn ($q, $s) => $q->where('nama', 'like', "%{$s}%"))
            ->when($request->kategori, fn ($q, $k) => $q->where('kategori_id', $k))
            ->when($request->brand, fn ($q, $b) => $q->where('brand_id', $b))
            ->latest()
            ->paginate(12);

        $brands = Brand::orderBy('nama')->get();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('home', compact('produk', 'brands', 'kategoris'));
    }

    /**
     * Menampilkan detail produk.
     */
    public function show(Produk $produk): View
    {
        $produk->load(['brand', 'kategori']);

        return view('produk.show', compact('produk'));
    }
}
