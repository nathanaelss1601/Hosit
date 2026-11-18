@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-people"></i> Data Karyawan</h2>
        <p class="text-muted mb-0">Informasi karyawan, tanggal masuk, kontak, dan total absensi.</p>
    </div>
    <a href="{{ route('employees.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Karyawan
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-list-ul"></i> Daftar Karyawan</span>
        <span class="badge bg-light text-dark">Total: {{ $employees->count() }}</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Telepon</th>
                        <th>Tanggal Masuk</th>
                        <th>Gaji Harian</th>
                        <th>Absensi</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td><strong>{{ $employee->employee_code }}</strong></td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->position ?? '-' }}</td>
                            <td>{{ $employee->phone ?? '-' }}</td>
                            <td>{{ $employee->hire_date ? $employee->hire_date->format('d M Y') : '-' }}</td>
                            <td>Rp {{ number_format($employee->daily_salary ?? 0, 0, ',', '.') }}</td>
                            <td><span class="badge bg-info text-dark">{{ $employee->attendance_count }}</span></td>
                            <td>
                                @if($employee->status === 'active')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> Belum ada data karyawan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection