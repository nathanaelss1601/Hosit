@extends('layouts.app')

@section('title', 'Data Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-receipt"></i> Data Transaksi</h2>
        <p class="text-muted mb-0">Daftar seluruh transaksi penjualan.</p>
    </div>
    <a href="{{ route('pos') }}" class="btn btn-primary">
        <i class="bi bi-cart-check"></i> Buat Transaksi Baru
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-list-ul"></i> Riwayat Transaksi</span>
        <span class="badge bg-light text-dark">Total: {{ $transactions->total() }}</span>
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
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td><strong>{{ $transaction->invoice_number }}</strong></td>
                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $transaction->user->name ?? '-' }}</td>
                            <td>{{ $transaction->customer_name ?: '-' }}</td>
                            <td class="text-uppercase">{{ $transaction->payment_method }}</td>
                            <td class="fw-semibold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                            <td>
                                @if($transaction->status === 'completed')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($transaction->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-danger">Batal</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> Belum ada transaksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="mt-3">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
