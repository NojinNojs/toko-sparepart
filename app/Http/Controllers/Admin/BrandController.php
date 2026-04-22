<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandController extends Controller
{
    /**
     * Menampilkan daftar semua merek.
     */
    public function index(): View
    {
        $brands = Brand::withCount('produk')
            ->orderBy('nama')
            ->paginate(15);

        return view('admin.brand.index', compact('brands'));
    }

    /**
     * Menampilkan form tambah merek baru.
     */
    public function create(): View
    {
        return view('admin.brand.create');
    }

    /**
     * Menyimpan merek baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:brand,nama'],
        ], [
            'nama.required' => 'Nama merek wajib diisi.',
            'nama.unique' => 'Nama merek sudah digunakan.',
            'nama.max' => 'Nama merek maksimal :max karakter.',
        ]);

        Brand::create($validated);

        return redirect()
            ->route('admin.brand.index')
            ->with('success', 'Merek berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit merek.
     */
    public function edit(Brand $brand): View
    {
        return view('admin.brand.edit', compact('brand'));
    }

    /**
     * Memperbarui data merek di database.
     */
    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:brand,nama,'.$brand->id],
        ], [
            'nama.required' => 'Nama merek wajib diisi.',
            'nama.unique' => 'Nama merek sudah digunakan.',
            'nama.max' => 'Nama merek maksimal :max karakter.',
        ]);

        $brand->update($validated);

        return redirect()
            ->route('admin.brand.index')
            ->with('success', 'Merek berhasil diperbarui!');
    }

    /**
     * Menghapus merek dari database.
     */
    public function destroy(Brand $brand): RedirectResponse
    {
        try {
            $brand->delete();

            return redirect()
                ->route('admin.brand.index')
                ->with('success', 'Merek berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus merek. Merek mungkin masih memiliki produk terkait.');
        }
    }
}
