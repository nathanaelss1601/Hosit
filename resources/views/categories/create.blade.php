@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="mb-4">
    <h2 class="mb-1"><i class="bi bi-plus-circle"></i> Tambah Kategori</h2>
    <p class="text-muted mb-0">Buat kategori produk baru.</p>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil-square"></i> Form Kategori
    </div>
    <div class="card-body">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Kategori</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input id="is_active" type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">Kategori aktif</label>
                    </div>
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">Deskripsi (opsional)</label>
                    <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
