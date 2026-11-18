@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="mb-4">
    <h2 class="mb-1"><i class="bi bi-pencil"></i> Edit Produk</h2>
    <p class="text-muted mb-0">Perbarui informasi produk.</p>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil-square"></i> Form Edit Produk
    </div>
    <div class="card-body">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="category_id" class="form-label">Kategori (opsional)</label>
                    <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">Tanpa kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="price" class="form-label">Harga Jual</label>
                    <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}" class="form-control @error('price') is-invalid @enderror" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="stock" class="form-label">Stok</label>
                    <input id="stock" type="number" min="0" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control @error('stock') is-invalid @enderror" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">Deskripsi (opsional)</label>
                    <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="image" class="form-label">Gambar (opsional)</label>
                    <input id="image" type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($product->image)
                        <div class="form-text">
                            Gambar saat ini: <a href="{{ asset('storage/' . $product->image) }}" target="_blank">Lihat gambar</a>
                        </div>
                    @endif
                </div>

                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input type="hidden" name="is_available" value="0">
                        <input id="is_available" type="checkbox" name="is_available" value="1" class="form-check-input" {{ old('is_available', $product->is_available) ? 'checked' : '' }}>
                        <label for="is_available" class="form-check-label">Produk tersedia untuk dijual</label>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
