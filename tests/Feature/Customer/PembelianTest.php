<?php

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\User;

it('customer can view own riwayat', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $brand = Brand::create(['nama' => 'K', 'slug' => 'k']);
    $kategori = Kategori::create(['nama' => 'B', 'slug' => 'b']);
    $produk = Produk::create(['kode' => 'T1', 'nama' => 'P', 'harga' => 10000, 'stok' => 10, 'deskripsi' => 'D', 'gambar' => 'g', 'brand_id' => $brand->id, 'kategori_id' => $kategori->id]);
    Pembelian::create(['invoice_no' => 'I1', 'user_id' => $customer->id, 'produk_id' => $produk->id, 'jumlah' => 1, 'total' => 10, 'status' => 'pending']);

    $response = $this->actingAs($customer)->get(route('pembelian.index'));
    $response->assertStatus(200);
});
