<?php

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\User;

it('allows customer to checkout', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $brand = Brand::create(['nama' => 'K', 'slug' => 'k']);
    $kategori = Kategori::create(['nama' => 'B', 'slug' => 'b']);
    $produk = Produk::create(['kode' => 'T1', 'nama' => 'P', 'harga' => 10000, 'stok' => 10, 'deskripsi' => 'D', 'gambar' => 'g', 'brand_id' => $brand->id, 'kategori_id' => $kategori->id]);

    $response = $this->actingAs($customer)->post(route('checkout.store'), [
        'produk_id' => $produk->id,
        'jumlah' => 2,
    ]);
    $response->assertRedirect(route('pembelian.index'));
    expect(Pembelian::count())->toBe(1);
    expect($produk->refresh()->stok)->toBe(8);
});
