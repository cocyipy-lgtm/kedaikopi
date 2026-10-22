<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu | Coffee Break</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-body">
    <main class="admin-container form-container">
        <header class="admin-header">
            <div>
                <a class="admin-brand" href="{{ route('home') }}">Coffee <span>Break.</span></a>
                <h1>Tambah Menu</h1>
                <p>Menu baru akan langsung tampil di website jika tersedia.</p>
            </div>
            <a class="button button-secondary" href="{{ route('admin.products.index') }}">Kembali</a>
        </header>

        @include('admin.products.form', ['submitLabel' => 'Simpan menu'])
    </main>
</body>
</html>
