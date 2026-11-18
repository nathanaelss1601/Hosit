@extends('layouts.app')

@section('title', 'Detail Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-tags"></i> Detail Kategori</h2>
        <p class="text-muted mb-0">Informasi kategori dan daftar produk terkait.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-info-circle"></i> Informasi Kategori
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="text-muted small">Nama</div>
                <div class="fw-semibold">{{ $category->name }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Status</div>
                <div>
                    @if($category->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </div>
            </div>
            <div class="col-12">
                <div class="text-muted small">Deskripsi</div>
                <div class="p-3 rounded" style="background-color: #fffdf4; border: 1px solid #ffe08a;">
                    {{ $category->description ?: '-' }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-box-seam"></i> Produk Dalam Kategori ({{ $category->products->count() }})
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($category->products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                @if($product->is_available)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada produk di kategori ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
