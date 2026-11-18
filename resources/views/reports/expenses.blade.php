@extends('layouts.app')

@section('title', 'Laporan Pengeluaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-cash-stack"></i> Laporan Pengeluaran</h2>
        <p class="text-muted mb-0">Detail pengeluaran dalam periode terpilih.</p>
    </div>
    <a href="{{ route('reports.index', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Ringkasan
    </a>
</div>

@include('reports.partials.date-filter', [
    'action' => route('reports.expenses'),
    'resetRoute' => route('reports.expenses'),
    'startDate' => $startDate,
    'endDate' => $endDate,
])

<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-table"></i> Daftar Pengeluaran</span>
        <span class="badge bg-light text-dark">Total: Rp {{ number_format($totalExpenses, 0, ',', '.') }}</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Nominal</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
                            <td>{{ $expense->category }}</td>
                            <td>{{ $expense->description }}</td>
                            <td class="text-danger">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                            <td>{{ $expense->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data pengeluaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($expenses->hasPages())
            <div class="mt-3">
                {{ $expenses->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
