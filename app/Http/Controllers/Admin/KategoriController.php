<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriController extends Controller
{
    /**
     * Menampilkan daftar semua kategori.
     */
    public function index(): View
    {
        $kategoris = Kategori::withCount('produk')
            ->orderBy('nama')
            ->paginate(15);

        return view('admin.kategori.index', compact('kategoris'));
    }

    /**
     * Menampilkan form tambah kategori baru.
     */
    public function create(): View
    {
        return view('admin.kategori.create');
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:kategori,nama'],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique' => 'Nama kategori sudah digunakan.',
            'nama.max' => 'Nama kategori maksimal :max karakter.',
        ]);

        Kategori::create($validated);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit kategori.
     */
    public function edit(Kategori $kategori): View
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Memperbarui data kategori di database.
     */
    public function update(Request $request, Kategori $kategori): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:kategori,nama,'.$kategori->id],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique' => 'Nama kategori sudah digunakan.',
            'nama.max' => 'Nama kategori maksimal :max karakter.',
        ]);

        $kategori->update($validated);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy(Kategori $kategori): RedirectResponse
    {
        try {
            $kategori->delete();

            return redirect()
                ->route('admin.kategori.index')
                ->with('success', 'Kategori berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus kategori. Kategori mungkin masih memiliki produk terkait.');
        }
    }
}
