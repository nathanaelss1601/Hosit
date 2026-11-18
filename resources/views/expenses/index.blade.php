@extends('layouts.app')

@section('title', 'Data Pengeluaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-cash-stack"></i> Data Pengeluaran</h2>
        <p class="text-muted mb-0">Kelola semua data pengeluaran restoran.</p>
    </div>
    <a href="{{ route('expenses.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Pengeluaran
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-list-ul"></i> Daftar Pengeluaran</span>
        <span class="badge bg-light text-dark">Total: {{ $expenses->total() }}</span>
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
                        <th>Dicatat Oleh</th>
                        <th>Bukti</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">{{ $expense->category }}</span>
                            </td>
                            <td>{{ $expense->description }}</td>
                            <td class="text-danger fw-semibold">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                            <td>{{ $expense->user->name ?? '-' }}</td>
                            <td>
                                @if($expense->receipt_image_url)
                                    <a href="{{ $expense->receipt_image_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-image"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data pengeluaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> Belum ada data pengeluaran
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($expenses->hasPages())
            <div class="mt-3">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
