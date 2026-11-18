@extends('layouts.app')

@section('title', 'Edit Pengeluaran')

@section('content')
<div class="mb-4">
    <h2 class="mb-1"><i class="bi bi-pencil"></i> Edit Pengeluaran</h2>
    <p class="text-muted mb-0">Perbarui data pengeluaran yang dipilih.</p>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil-square"></i> Form Edit Pengeluaran
    </div>
    <div class="card-body">
        <form action="{{ route('expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="category" class="form-label">Kategori</label>
                    <select id="category" name="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ old('category', $expense->category) === $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="amount" class="form-label">Nominal</label>
                    <input id="amount" type="number" step="0.01" min="0" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $expense->amount) }}" required>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="expense_date" class="form-label">Tanggal Pengeluaran</label>
                    <input id="expense_date" type="date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
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
                    @if($expense->receipt_image_url)
                        <div class="form-text">
                            Bukti saat ini: <a href="{{ $expense->receipt_image_url }}" target="_blank" rel="noopener noreferrer">Lihat gambar</a>
                        </div>
                    @endif
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $expense->description) }}</textarea>
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
                    <i class="bi bi-save"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
