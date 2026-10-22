<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu | Coffee Break</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-body">
    <main class="admin-container">
        <header class="admin-header">
            <div>
                <a class="admin-brand" href="{{ route('home') }}">Coffee <span>Break.</span></a>
                <h1>Kelola Menu</h1>
                <p>Tambah, ubah, dan atur ketersediaan menu kedai.</p>
            </div>
            <div class="header-actions">
                <a class="button button-secondary" href="{{ route('home') }}">Lihat website</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="button button-secondary" type="submit">Keluar</button>
                </form>
            </div>
        </header>

        @if (session('status'))
            <div class="alert alert-success" role="status">{{ session('status') }}</div>
        @endif

        <section class="panel">
            <div class="panel-heading">
                <div>
                    <h2>Daftar menu</h2>
                    <p>{{ $products->total() }} menu tersimpan</p>
                </div>
                <a class="button button-primary" href="{{ route('admin.products.create') }}">+ Tambah menu</a>
            </div>

            <div class="table-wrap">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    <div class="product-cell">
                                        <img src="{{ $product->image_url }}" alt="" class="product-thumbnail">
                                        <div>
                                            <strong>{{ $product->name }}</strong>
                                            <p>{{ $product->description }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->category }}</td>
                                <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="status-badge {{ $product->is_available ? 'status-available' : 'status-unavailable' }}">
                                        {{ $product->is_available ? 'Tersedia' : 'Habis' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a class="button button-small button-secondary" href="{{ route('admin.products.edit', $product) }}">Edit</a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus menu ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="button button-small button-danger" type="submit">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="empty-state" colspan="5">Belum ada menu. Tambahkan menu pertama kamu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">{{ $products->links() }}</div>
        </section>
    </main>
</body>
</html>
