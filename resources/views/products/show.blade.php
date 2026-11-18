@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-box-seam"></i> Detail Produk</h2>
        <p class="text-muted mb-0">Informasi lengkap produk.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-info-circle"></i> Informasi Produk
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-4">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded border">
                @else
                    <div class="border rounded p-5 text-center text-muted">
                        <i class="bi bi-image fs-1"></i>
                        <div>Tidak ada gambar</div>
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Nama</div>
                        <div class="fw-semibold">{{ $product->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Kategori</div>
                        <div class="fw-semibold">{{ $product->category->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Harga Jual</div>
                        <div class="fw-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Stok</div>
                        <div>
                            <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">{{ $product->stock }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Status</div>
                        <div>
                            @if($product->is_available)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Deskripsi</div>
                        <div class="p-3 rounded" style="background-color: #fffdf4; border: 1px solid #ffe08a;">
                            {{ $product->description ?: '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
