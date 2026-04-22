<?php

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;

it('allows admin to create produk', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $brand = Brand::create(['nama' => 'Honda', 'slug' => 'honda']);
    $kategori = Kategori::create(['nama' => 'Mesin', 'slug' => 'mesin']);

    $response = $this->actingAs($admin)->post(route('admin.produk.store'), [
        'kode' => 'SP001',
        'nama' => 'Filter Oli',
        'deskripsi' => 'Filter oli berkualitas',
        'harga' => 50000,
        'stok' => 100,
        'brand_id' => $brand->id,
        'kategori_id' => $kategori->id,
    ]);

    $response->assertRedirect(route('admin.produk.index'));
    expect(Produk::count())->toBe(1);
});

it('prevents customer from accessing admin produk', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $this->actingAs($customer)
        ->get(route('admin.produk.index'))
        ->assertForbidden();
});
