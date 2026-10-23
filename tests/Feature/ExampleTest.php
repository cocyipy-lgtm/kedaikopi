<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('the homepage generates HTTPS asset URLs behind a TLS-terminating proxy', function () {
    $response = $this
        ->withHeaders([
            'X-Forwarded-Host' => 'kedaikopi-production-c011.up.railway.app',
            'X-Forwarded-Proto' => 'https',
        ])
        ->get('/');

    $response
        ->assertOk()
        ->assertSee('https://kedaikopi-production-c011.up.railway.app/css/style.css', false)
        ->assertSee('https://kedaikopi-production-c011.up.railway.app/images/main-1.jpg', false);
});
