<?php

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\User;

it('has admin role checker', function () {
    $admin = User::factory()->make(['role' => 'admin']);
    expect($admin->isAdmin())->toBeTrue();

    $customer = User::factory()->make(['role' => 'customer']);
    expect($customer->isAdmin())->toBeFalse();
});

it('has many pembelian relationship', function () {
    $user = User::factory()->create();
    $brand = Brand::create(['nama' => 'Test Brand', 'slug' => 'test-brand']);
    $kategori = Kategori::create(['nama' => 'Test Kategori', 'slug' => 'test-kategori']);
    $produk = Produk::create(['kode' => 'T001', 'nama' => 'P', 'harga' => 10, 'stok' => 10, 'deskripsi' => 'D', 'gambar' => 'g', 'brand_id' => $brand->id, 'kategori_id' => $kategori->id]);

    $pembelian = Pembelian::create([
        'invoice_no' => 'INV-001',
        'user_id' => $user->id,
        'produk_id' => $produk->id,
        'jumlah' => 1,
        'total' => 10000,
        'status' => 'pending',
    ]);

    expect($user->pembelian)->toHaveCount(1);
    expect($user->pembelian->first()->id)->toBe($pembelian->id);
});
