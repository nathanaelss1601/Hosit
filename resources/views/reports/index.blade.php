@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-graph-up-arrow"></i> Laporan Keuangan</h2>
        <p class="text-muted mb-0">Ringkasan pemasukan, pengeluaran, dan laba bersih.</p>
    </div>
    <a href="{{ route('reports.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success">
        <i class="bi bi-download"></i> Export CSV
    </a>
</div>

@include('reports.partials.date-filter', [
    'action' => route('reports.index'),
    'resetRoute' => route('reports.index'),
    'startDate' => $startDate,
    'endDate' => $endDate,
])

<div class="row mt-1">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="label">Total Pemasukan</div>
            <div class="value" style="font-size: 1.7rem;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="label">Total Pengeluaran</div>
            <div class="value text-danger" style="font-size: 1.7rem;">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="label">Laba Bersih</div>
            <div class="value" style="font-size: 1.7rem; color: {{ $netProfit >= 0 ? '#198754' : '#dc3545' }};">
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
        <div class="row">
            <div class="col-md-6">
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
            <div class="col-md-6">
                <div class="alert {{ $netProfit >= 0 ? 'alert-success' : 'alert-danger' }} mb-0">
                    @if($netProfit >= 0)
                        <i class="bi bi-check-circle"></i> Usaha masih menghasilkan laba bersih pada periode ini.
                    @else
                        <i class="bi bi-exclamation-triangle"></i> Periode ini mengalami rugi bersih. Evaluasi pemasukan dan biaya operasional.
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-star"></i> Top Produk Terlaris
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Omzet</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $product)
                                <tr>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ number_format($product->total_qty, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($product->total_sales, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Belum ada data penjualan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pie-chart"></i> Pengeluaran per Kategori
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expensesByCategory as $expense)
                                <tr>
                                    <td>{{ $expense->category }}</td>
                                    <td class="text-danger">Rp {{ number_format($expense->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">Belum ada data pengeluaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-2">
    <div class="card-header">
        <i class="bi bi-calendar3"></i> Data Harian (Pemasukan vs Pengeluaran)
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pemasukan</th>
                        <th>Pengeluaran</th>
                        <th>Profit Harian</th>
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
                            <td colspan="4" class="text-center text-muted py-3">Belum ada data harian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-3">
    <a href="{{ route('reports.sales', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline-primary">
        <i class="bi bi-receipt"></i> Detail Penjualan
    </a>
    <a href="{{ route('reports.profit', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline-primary">
        <i class="bi bi-currency-dollar"></i> Detail Profit
    </a>
    <a href="{{ route('reports.expenses', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline-primary">
        <i class="bi bi-cash-stack"></i> Detail Pengeluaran
    </a>
</div>
@endsection
