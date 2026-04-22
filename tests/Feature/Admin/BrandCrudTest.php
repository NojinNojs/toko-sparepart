<?php

use App\Models\Brand;
use App\Models\User;

it('allows admin to create brand', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($admin)->post(route('admin.brand.store'), [
        'nama' => 'Yamaha',
    ]);
    $response->assertRedirect(route('admin.brand.index'));
    expect(Brand::count())->toBe(1);
});
