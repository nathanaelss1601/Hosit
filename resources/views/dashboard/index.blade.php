@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
    <p class="text-muted">Selamat datang, {{ auth()->user()->name }}!</p>
    <p class="text-muted"><i class="bi bi-calendar"></i> {{ now()->format('l, d F Y') }}</p>
</div>

<!-- Stats Cards -->
<div class="row mb-5">
    <div class="col-md-3 mb-4">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="icon">
                    <i class="bi bi-calendar-day"></i>
                </div>
                <div class="ms-3 flex-grow-1">
                    <div class="label">Pemasukan Hari Ini</div>
                    <div class="value">Rp {{ number_format($todayIncome, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="icon">
                    <i class="bi bi-calendar-month"></i>
                </div>
                <div class="ms-3 flex-grow-1">
                    <div class="label">Pemasukan Bulan Ini</div>
                    <div class="value">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    
    @if(auth()->user()->isAdmin())
    <div class="col-md-3 mb-4">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="icon" style="background: linear-gradient(135deg, #dc3545, #c82333);">
                    <i class="bi bi-cash-stack" style="color: white;"></i>
                </div>
                <div class="ms-3 flex-grow-1">
                    <div class="label">Pengeluaran Hari Ini</div>
                    <div class="value" style="color: #dc3545;">Rp {{ number_format($todayExpenses, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="icon" style="background: linear-gradient(135deg, #28a745, #218838);">
                    <i class="bi bi-graph-up" style="color: white;"></i>
                </div>
                <div class="ms-3 flex-grow-1">
                    <div class="label">Profit Hari Ini</div>
                    <div class="value" style="color: {{ $todayProfit >= 0 ? '#28a745' : '#dc3545' }};">
                        Rp {{ number_format($todayProfit, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Recent Transactions -->
<div class="row mb-5">
    <div class="col-md-12">
        <div class="card mb-5">
            <div class="card-header">
                <i class="bi bi-receipt"></i> Transaksi Terbaru
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>Kasir</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $transaction)
                            <tr>
                                <td><strong>{{ $transaction->invoice_number }}</strong></td>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $transaction->user->name }}</td>
                                <td><strong>Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($transaction->status == 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-warning">{{ ucfirst($transaction->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <i class="bi bi-inbox"></i> Belum ada transaksi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="text-end mt-3">
                    <a href="{{ route('transactions.index') }}" class="btn btn-primary btn-sm">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->isAdmin())
<!-- Recent Expenses -->
<div class="row mb-5">
    <div class="col-md-12">
        <div class="card mb-5">
            <div class="card-header">
                <i class="bi bi-cash-stack"></i> Pengeluaran Terbaru
            </div>
            <div class="card-body">
                @forelse($recentExpenses as $expense)
                <div class="mb-2 pb-2 border-bottom">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $expense->category }}</strong>
                        <span class="text-danger">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                    </div>
                    <small class="text-muted">{{ $expense->expense_date->format('d/m/Y') }}</small>
                </div>
                @empty
                <p class="text-muted mb-0"><i class="bi bi-inbox"></i> Belum ada pengeluaran</p>
                @endforelse
                
                @if($recentExpenses->count() > 0)
                <div class="text-end mt-3">
                    <a href="{{ route('expenses.index') }}" class="btn btn-primary btn-sm">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

@if(auth()->user()->isAdmin())
<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-bar-chart"></i> Quick Stats Bulanan
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h3 style="color: var(--primary-yellow);">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</h3>
                        <p class="text-muted">Total Pemasukan</p>
                    </div>
                    <div class="col-md-3">
                        <h3 style="color: #dc3545;">Rp {{ number_format($monthlyExpenses, 0, ',', '.') }}</h3>
                        <p class="text-muted">Total Pengeluaran</p>
                    </div>
                    <div class="col-md-3">
                        <h3 style="color: {{ $monthlyProfit >= 0 ? '#28a745' : '#dc3545' }};">
                            Rp {{ number_format($monthlyProfit, 0, ',', '.') }}
                        </h3>
                        <p class="text-muted">Profit Bersih</p>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('reports.index') }}" class="btn btn-primary btn-lg mt-2">
                            <i class="bi bi-graph-up"></i> Lihat Laporan Lengkap
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
