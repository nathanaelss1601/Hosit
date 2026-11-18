@extends('layouts.app')

@section('title', 'Detail Pengeluaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-receipt-cutoff"></i> Detail Pengeluaran</h2>
        <p class="text-muted mb-0">Ringkasan detail transaksi pengeluaran.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-info-circle"></i> Informasi Pengeluaran
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small">Tanggal</div>
                <div class="fw-semibold">{{ $expense->expense_date->format('d/m/Y') }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Kategori</div>
                <div><span class="badge bg-warning text-dark">{{ $expense->category }}</span></div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Nominal</div>
                <div class="fw-bold text-danger">Rp {{ number_format($expense->amount, 0, ',', '.') }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Dicatat Oleh</div>
                <div class="fw-semibold">{{ $expense->user->name ?? '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Dibuat Pada</div>
                <div class="fw-semibold">{{ $expense->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="col-12">
                <div class="text-muted small">Deskripsi</div>
                <div class="p-3 rounded" style="background-color: #fffdf4; border: 1px solid #ffe08a;">
                    {{ $expense->description }}
                </div>
            </div>
        </div>
    </div>
</div>

@if($expense->receipt_image_url)
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-image"></i> Bukti Pengeluaran</span>
            <a href="{{ $expense->receipt_image_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-box-arrow-up-right"></i> Buka di tab baru
            </a>
        </div>
        <div class="card-body">
            <img src="{{ $expense->receipt_image_url }}" alt="Bukti Pengeluaran" class="img-fluid rounded border" style="max-height: 480px;">
        </div>
    </div>
@endif
@endsection
