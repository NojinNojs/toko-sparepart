<?php

use App\Models\User;

it('prevents customer from accessing admin dashboard', function () {
    $customer = User::factory()->create(['role' => 'customer']);
    $this->actingAs($customer)->get(route('admin.dashboard'))->assertForbidden();
});

it('prevents admin from checkout', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin)->post(route('checkout.store'))->assertForbidden();
});
