<?php

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Produk;

it('belongs to brand and kategori', function () {
    $brand = Brand::create(['nama' => 'Test Brand', 'slug' => 'test-brand']);
    $kategori = Kategori::create(['nama' => 'Test Kategori', 'slug' => 'test-kategori']);
    $produk = Produk::create([
        'kode' => 'T001',
        'nama' => 'Produk Test',
        'harga' => 10000,
        'stok' => 10,
        'deskripsi' => 'Test',
        'gambar' => 'test.jpg',
        'brand_id' => $brand->id,
        'kategori_id' => $kategori->id,
    ]);

    expect($produk->brand->id)->toBe($brand->id);
    expect($produk->kategori->id)->toBe($kategori->id);
});
