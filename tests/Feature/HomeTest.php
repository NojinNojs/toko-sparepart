<?php

it('allows guest to view home', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});
