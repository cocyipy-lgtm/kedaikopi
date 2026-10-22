<section class="panel">
    @if ($errors->any())
        <div class="alert alert-error" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
        enctype="multipart/form-data"
        class="admin-form"
    >
        @csrf
        @isset($product)
            @method('PUT')
        @endisset

        <label for="name">Nama menu</label>
        <input id="name" name="name" type="text" value="{{ old('name', $product->name ?? '') }}" maxlength="255" required>

        <label for="category">Kategori</label>
        <select id="category" name="category" required>
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(old('category', $product->category ?? '') === $category)>
                    {{ $category }}
                </option>
            @endforeach
        </select>

        <label for="price">Harga (Rupiah)</label>
        <input id="price" name="price" type="number" min="1" max="100000000" value="{{ old('price', $product->price ?? '') }}" required>

        <label for="description">Deskripsi</label>
        <textarea id="description" name="description" rows="4" maxlength="2000" required>{{ old('description', $product->description ?? '') }}</textarea>

        @isset($product)
            <div class="current-image">
                <img src="{{ $product->image_url }}" alt="Foto {{ $product->name }}">
                <span>Foto saat ini</span>
            </div>
        @endisset

        <label for="image">Foto menu <span class="muted">(opsional, JPG/PNG/WebP maks. 2 MB)</span></label>
        <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

        <label class="checkbox-label">
            <input name="is_available" type="checkbox" value="1" @checked(old('is_available', $product->is_available ?? true))>
            Tampilkan sebagai menu tersedia
        </label>

        <div class="form-actions">
            <button class="button button-primary" type="submit">{{ $submitLabel }}</button>
            <a class="button button-secondary" href="{{ route('admin.products.index') }}">Batal</a>
        </div>
    </form>
</section>
