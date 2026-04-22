<?php

use App\Models\Kategori;
use App\Models\User;

it('allows admin to create kategori', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($admin)->post(route('admin.kategori.store'), [
        'nama' => 'Ban',
    ]);
    $response->assertRedirect(route('admin.kategori.index'));
    expect(Kategori::count())->toBe(1);
});
