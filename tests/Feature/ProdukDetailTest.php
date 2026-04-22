<?php

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Produk;

it('shows produk detail', function () {
    $brand = Brand::create(['nama' => 'Test Brand', 'slug' => 'test-brand']);
    $kategori = Kategori::create(['nama' => 'Test Kategori', 'slug' => 'test-kategori']);
    $produk = Produk::create(['kode' => 'T001', 'nama' => 'P', 'harga' => 10, 'stok' => 10, 'deskripsi' => 'D', 'gambar' => 'g', 'brand_id' => $brand->id, 'kategori_id' => $kategori->id]);

    $response = $this->get(route('produk.show', $produk));
    $response->assertStatus(200);
});
