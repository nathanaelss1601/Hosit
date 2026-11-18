@extends('layouts.app')

@section('title', 'Tambah Pengeluaran')

@section('content')
<div class="mb-4">
    <h2 class="mb-1"><i class="bi bi-plus-circle"></i> Tambah Pengeluaran</h2>
    <p class="text-muted mb-0">Isi form berikut untuk mencatat pengeluaran baru.</p>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil-square"></i> Form Pengeluaran
    </div>
    <div class="card-body">
        <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="category" class="form-label">Kategori</label>
                    <select id="category" name="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="amount" class="form-label">Nominal</label>
                    <input id="amount" type="number" step="0.01" min="0" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="expense_date" class="form-label">Tanggal Pengeluaran</label>
                    <input id="expense_date" type="date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror" value="{{ old('expense_date', now()->format('Y-m-d')) }}" required>
                    @error('expense_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="receipt_image" class="form-label">Bukti Pengeluaran (opsional)</label>
                    <input id="receipt_image" type="file" name="receipt_image" accept="image/*" class="form-control @error('receipt_image') is-invalid @enderror">
                    @error('receipt_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
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
