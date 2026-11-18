@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-receipt-cutoff"></i> Detail Transaksi</h2>
        <p class="text-muted mb-0">Invoice: <strong>{{ $transaction->invoice_number }}</strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('pos') }}" class="btn btn-primary">
            <i class="bi bi-cart-check"></i> POS
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-info-circle"></i> Informasi Transaksi
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small">Tanggal</div>
                <div class="fw-semibold">{{ $transaction->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Kasir</div>
                <div class="fw-semibold">{{ $transaction->user->name ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Status</div>
                <div>
                    @if($transaction->status === 'completed')
                        <span class="badge bg-success">Selesai</span>
                    @elseif($transaction->status === 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @else
                        <span class="badge bg-danger">Batal</span>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Pelanggan</div>
                <div class="fw-semibold">{{ $transaction->customer_name ?: '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Metode Bayar</div>
                <div class="fw-semibold text-uppercase">{{ $transaction->payment_method }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-bag"></i> Item Pembelian
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaction->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Tidak ada item.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end mt-4">
            <div class="col-md-5">
                <div class="d-flex justify-content-between py-1">
                    <span>Subtotal</span>
                    <strong>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>Pajak</span>
                    <strong>Rp {{ number_format($transaction->tax, 0, ',', '.') }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>Diskon</span>
                    <strong>Rp {{ number_format($transaction->discount, 0, ',', '.') }}</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between py-1 fs-5">
                    <span>Total</span>
                    <strong>Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>Bayar</span>
                    <strong>Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>Kembalian</span>
                    <strong>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
