@extends('layouts.app')

@section('title', 'Log Absensi Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-calendar-check"></i> Log Absensi Karyawan</h2>
        <p class="text-muted mb-0">Admin mencatat absensi karyawan dari halaman ini.</p>
    </div>
    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-people"></i> Data Karyawan
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-plus-circle"></i> Catat Absensi
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('employees.attendance-store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Karyawan</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">Pilih karyawan</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>
                                    {{ $employee->name }} ({{ $employee->employee_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Absensi</label>
                        <input type="date" name="attendance_date" class="form-control" value="{{ old('attendance_date', now()->toDateString()) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" name="checked_in_at" class="form-control" value="{{ old('checked_in_at', now()->format('H:i')) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Opsional">{{ old('notes') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save"></i> Simpan Absensi
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-check"></i> Riwayat Log</span>
                <span class="badge bg-light text-dark">Total: {{ $attendanceLogs->total() }}</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Karyawan</th>
                                <th>Jam</th>
                                <th>Dicatat Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendanceLogs as $log)
                                <tr>
                                    <td>{{ $log->attendance_date->format('d M Y') }}</td>
                                    <td>
                                        <strong>{{ $log->employee->name ?? '-' }}</strong><br>
                                        <small class="text-muted">{{ $log->employee->employee_code ?? '-' }}</small>
                                    </td>
                                    <td>{{ $log->checked_in_at ? $log->checked_in_at : '-' }}</td>
                                    <td>{{ $log->user->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada log absensi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($attendanceLogs->hasPages())
                    <div class="mt-3">
                        {{ $attendanceLogs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection