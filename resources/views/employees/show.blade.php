@extends('layouts.app')

@section('title', 'Detail Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-person-badge"></i> Detail Karyawan</h2>
        <p class="text-muted mb-0">Informasi lengkap dan riwayat absensi karyawan.</p>
    </div>
    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">Profil</h5>
                <dl class="row mb-0">
                    <dt class="col-5">Kode</dt><dd class="col-7">{{ $employee->employee_code }}</dd>
                    <dt class="col-5">Nama</dt><dd class="col-7">{{ $employee->name }}</dd>
                    <dt class="col-5">Jabatan</dt><dd class="col-7">{{ $employee->position ?? '-' }}</dd>
                    <dt class="col-5">Telepon</dt><dd class="col-7">{{ $employee->phone ?? '-' }}</dd>
                    <dt class="col-5">Tanggal Masuk</dt><dd class="col-7">{{ $employee->hire_date ? $employee->hire_date->format('d M Y') : '-' }}</dd>
                    <dt class="col-5">Gaji Harian</dt><dd class="col-7">Rp {{ number_format($employee->daily_salary ?? 0, 0, ',', '.') }}</dd>
                    <dt class="col-5">Total Absensi</dt><dd class="col-7">{{ $employee->attendance_count }}</dd>
                    <dt class="col-5">Status</dt><dd class="col-7">{{ ucfirst($employee->status) }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history"></i> Riwayat Absensi
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    Estimasi gaji berdasarkan absensi: <strong>Rp {{ number_format(($employee->daily_salary ?? 0) * $employee->attendance_count, 0, ',', '.') }}</strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Dicatat Oleh</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->attendanceLogs as $log)
                                <tr>
                                    <td>{{ $log->attendance_date->format('d M Y') }}</td>
                                    <td>{{ $log->checked_in_at ? $log->checked_in_at : '-' }}</td>
                                    <td>{{ $log->user->name ?? '-' }}</td>
                                    <td>{{ $log->notes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada log absensi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection