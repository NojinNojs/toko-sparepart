<?php

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Produk;

it('has many produk', function () {
    $brand = Brand::create(['nama' => 'Test Brand', 'slug' => 'test-brand']);
    $kategori = Kategori::create(['nama' => 'Test Kategori', 'slug' => 'test-kategori']);
    $produk = Produk::create(['kode' => 'T001', 'nama' => 'P', 'harga' => 10, 'stok' => 10, 'deskripsi' => 'D', 'gambar' => 'g', 'brand_id' => $brand->id, 'kategori_id' => $kategori->id]);

    expect($kategori->produk)->toHaveCount(1);
});
