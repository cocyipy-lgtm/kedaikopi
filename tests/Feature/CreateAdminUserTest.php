<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates an admin account with an eight-character password', function () {
    $this->artisan('app:create-admin')
        ->expectsQuestion('Nama admin', 'Admin Coffee')
        ->expectsQuestion('Email admin', 'admin@example.test')
        ->expectsQuestion('Password (minimal 8 karakter)', 'kopi1234')
        ->expectsQuestion('Ulangi password', 'kopi1234')
        ->expectsOutputToContain('Akun admin berhasil dibuat.')
        ->assertExitCode(0);

    $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();

    expect($admin->is_admin)->toBeTrue();
    expect($admin->password)->not->toBe('kopi1234');
    expect(password_verify('kopi1234', $admin->password))->toBeTrue();
});

it('rejects an admin password shorter than eight characters', function () {
    $this->artisan('app:create-admin')
        ->expectsQuestion('Nama admin', 'Admin Coffee')
        ->expectsQuestion('Email admin', 'admin@example.test')
        ->expectsQuestion('Password (minimal 8 karakter)', 'kopi123')
        ->expectsQuestion('Ulangi password', 'kopi123')
        ->expectsOutputToContain('at least 8 characters')
        ->assertExitCode(1);

    $this->assertDatabaseMissing('users', ['email' => 'admin@example.test']);
});
