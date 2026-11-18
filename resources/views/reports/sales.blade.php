@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-receipt"></i> Laporan Penjualan</h2>
        <p class="text-muted mb-0">Detail transaksi dalam periode terpilih.</p>
    </div>
    <a href="{{ route('reports.index', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Ringkasan
    </a>
</div>

@include('reports.partials.date-filter', [
    'action' => route('reports.sales'),
    'resetRoute' => route('reports.sales'),
    'startDate' => $startDate,
    'endDate' => $endDate,
])

<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-table"></i> Daftar Transaksi</span>
        <span class="badge bg-light text-dark">Total Sales: Rp {{ number_format($totalSales, 0, ',', '.') }}</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Pelanggan</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->invoice_number }}</td>
                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $transaction->user->name ?? '-' }}</td>
                            <td>{{ $transaction->customer_name ?: '-' }}</td>
                            <td class="text-uppercase">{{ $transaction->payment_method }}</td>
                            <td>
                                @if($transaction->status === 'completed')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($transaction->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-danger">Batal</span>
                                @endif
                            </td>
                            <td class="fw-semibold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="mt-3">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
