<?php

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\User;

it('belongs to user and produk, status default pending', function () {
    $user = User::factory()->create();
    $brand = Brand::create(['nama' => 'Test Brand', 'slug' => 'test-brand']);
    $kategori = Kategori::create(['nama' => 'Test Kategori', 'slug' => 'test-kategori']);
    $produk = Produk::create(['kode' => 'T001', 'nama' => 'P', 'harga' => 10, 'stok' => 10, 'deskripsi' => 'D', 'gambar' => 'g', 'brand_id' => $brand->id, 'kategori_id' => $kategori->id]);

    $pembelian = Pembelian::create(['invoice_no' => 'INV-001', 'user_id' => $user->id, 'produk_id' => $produk->id, 'jumlah' => 1, 'total' => 10]);

    expect($pembelian->user->id)->toBe($user->id);
    expect($pembelian->produk->id)->toBe($produk->id);
    expect($pembelian->refresh()->status)->toBe('pending');
});
