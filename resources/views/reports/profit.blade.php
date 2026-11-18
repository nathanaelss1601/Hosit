@extends('layouts.app')

@section('title', 'Laporan Profit')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-currency-dollar"></i> Laporan Profit</h2>
        <p class="text-muted mb-0">Perkiraan laba bersih dari pemasukan dan pengeluaran.</p>
    </div>
    <a href="{{ route('reports.index', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Ringkasan
    </a>
</div>

@include('reports.partials.date-filter', [
    'action' => route('reports.profit'),
    'resetRoute' => route('reports.profit'),
    'startDate' => $startDate,
    'endDate' => $endDate,
])

<div class="row mt-2">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="label">Total Pemasukan</div>
            <div class="value" style="font-size: 1.6rem;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="label">Total Pengeluaran</div>
            <div class="value text-danger" style="font-size: 1.6rem;">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="label">Laba Bersih</div>
            <div class="value" style="font-size: 1.6rem; color: {{ $netProfit >= 0 ? '#198754' : '#dc3545' }};">
                Rp {{ number_format($netProfit, 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <i class="bi bi-calculator"></i> Ringkasan Perhitungan
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between py-1">
            <span>Total Pemasukan</span>
            <strong>Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong>
        </div>
        <div class="d-flex justify-content-between py-1">
            <span>Total Pengeluaran</span>
            <strong class="text-danger">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</strong>
        </div>
        <hr>
        <div class="d-flex justify-content-between py-1 fs-5">
            <span>Laba Bersih (Pemasukan - Pengeluaran)</span>
            <strong style="color: {{ $netProfit >= 0 ? '#198754' : '#dc3545' }};">Rp {{ number_format($netProfit, 0, ',', '.') }}</strong>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <i class="bi bi-table"></i> Detail Profit Harian
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pemasukan</th>
                        <th>Pengeluaran</th>
                        <th>Laba Bersih</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyData as $row)
                        <tr>
                            <td>{{ $row['date'] }}</td>
                            <td>Rp {{ number_format($row['income'], 0, ',', '.') }}</td>
                            <td class="text-danger">Rp {{ number_format($row['expense'], 0, ',', '.') }}</td>
                            <td style="color: {{ $row['profit'] >= 0 ? '#198754' : '#dc3545' }};">
                                Rp {{ number_format($row['profit'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada data profit.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
