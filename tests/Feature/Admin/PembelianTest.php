<?php

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\User;

it('allows admin to update status', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'customer']);
    $brand = Brand::create(['nama' => 'K', 'slug' => 'k']);
    $kategori = Kategori::create(['nama' => 'B', 'slug' => 'b']);
    $produk = Produk::create(['kode' => 'T1', 'nama' => 'P', 'harga' => 10, 'stok' => 10, 'deskripsi' => 'D', 'gambar' => 'g', 'brand_id' => $brand->id, 'kategori_id' => $kategori->id]);
    $pembelian = Pembelian::create(['invoice_no' => 'I1', 'user_id' => $user->id, 'produk_id' => $produk->id, 'jumlah' => 1, 'total' => 10, 'status' => 'pending']);
    $response = $this->actingAs($admin)->patch(route('admin.pembelian.updateStatus', $pembelian), [
        'status' => 'dikonfirmasi',
    ]);
    $response->assertRedirect();
    expect($pembelian->refresh()->status)->toBe('dikonfirmasi');
});
