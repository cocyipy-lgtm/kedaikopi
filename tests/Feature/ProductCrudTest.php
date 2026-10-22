<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function productAttributes(array $overrides = []): array
{
    return array_merge([
        'name' => 'Es Kopi Susu',
        'category' => 'Kopi',
        'description' => 'Kopi susu dingin dengan gula aren.',
        'price' => 18000,
        'is_available' => true,
    ], $overrides);
}

it('redirects guests to the admin login page', function () {
    $this->get(route('admin.products.index'))
        ->assertRedirect(route('login'));
});

it('forbids an authenticated non-admin from managing menu items', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.products.index'))
        ->assertForbidden();
});

it('allows an administrator to log in', function () {
    $admin = User::factory()->create([
        'email' => 'admin@example.test',
        'password' => 'a-secure-password',
        'is_admin' => true,
    ]);

    $this->post(route('login.store'), [
        'email' => $admin->email,
        'password' => 'a-secure-password',
    ])->assertRedirect(route('admin.products.index'));

    $this->assertAuthenticatedAs($admin);
});

it('does not allow a regular user to log in as an administrator', function () {
    $user = User::factory()->create([
        'email' => 'member@example.test',
        'password' => 'a-secure-password',
        'is_admin' => false,
    ]);

    $this->from(route('login'))
        ->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'a-secure-password',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('creates a menu item with valid input', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->post(route('admin.products.store'), productAttributes())
        ->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseHas('products', [
        'name' => 'Es Kopi Susu',
        'category' => 'Kopi',
        'price' => 18000,
        'is_available' => true,
    ]);
});

it('rejects menu items with missing or invalid required fields', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->from(route('admin.products.create'))
        ->post(route('admin.products.store'), [
            'name' => '',
            'category' => 'Minuman',
            'description' => '',
            'price' => 0,
        ])
        ->assertSessionHasErrors(['name', 'category', 'description', 'price']);

    $this->assertDatabaseCount('products', 0);
});

it('stores a validated menu image on the public disk', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->post(route('admin.products.store'), productAttributes([
            'image' => UploadedFile::fake()->createWithContent(
                'menu.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADUlEQVR4nGP4z8AAAAMBAQDJ/pLvAAAAAElFTkSuQmCC'),
            ),
        ]))
        ->assertRedirect(route('admin.products.index'));

    $product = Product::query()->where('name', 'Es Kopi Susu')->firstOrFail();

    expect($product->image_path)->not->toBeNull();
    Storage::disk('public')->assertExists($product->image_path);
});

it('rejects non-image uploads', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->from(route('admin.products.create'))
        ->post(route('admin.products.store'), productAttributes([
            'image' => UploadedFile::fake()->create('malware.php', 12, 'text/plain'),
        ]))
        ->assertSessionHasErrors('image');

    $this->assertDatabaseCount('products', 0);
});

it('updates an existing menu item', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $product = Product::query()->create(productAttributes());

    $this->actingAs($admin)
        ->put(route('admin.products.update', $product), productAttributes([
            'name' => 'Es Kopi Aren',
            'price' => 20000,
            'is_available' => false,
        ]))
        ->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Es Kopi Aren',
        'price' => 20000,
        'is_available' => false,
    ]);
});

it('deletes an existing menu item', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $product = Product::query()->create(productAttributes());

    $this->actingAs($admin)
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

it('shows only available database menu items on the public website', function () {
    Product::query()->create(productAttributes(['name' => 'Menu Tersedia']));
    Product::query()->create(productAttributes([
        'name' => 'Menu Habis',
        'is_available' => false,
    ]));

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Menu Tersedia')
        ->assertDontSee('Menu Habis');
});

it('escapes product content before placing it in the page script', function () {
    $unsafeName = '</script><script>alert("xss")</script>';
    Product::query()->create(productAttributes(['name' => $unsafeName]));

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee($unsafeName, false);
});
