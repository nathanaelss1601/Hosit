@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="mb-4">
    <h2 class="mb-1"><i class="bi bi-plus-circle"></i> Tambah Karyawan</h2>
    <p class="text-muted mb-0">Tambahkan data karyawan baru ke sistem.</p>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil-square"></i> Form Karyawan
    </div>
    <div class="card-body">
        <form action="{{ route('employees.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Karyawan</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="position" class="form-label">Jabatan</label>
                    <input id="position" type="text" name="position" value="{{ old('position') }}" class="form-control @error('position') is-invalid @enderror" required>
                    @error('position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="phone" class="form-label">Nomor Telepon</label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="hire_date" class="form-label">Tanggal Masuk</label>
                    <input id="hire_date" type="date" name="hire_date" value="{{ old('hire_date') }}" class="form-control @error('hire_date') is-invalid @enderror" required>
                    @error('hire_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="daily_salary" class="form-label">Gaji Harian (Rp)</label>
                    <input id="daily_salary" type="number" name="daily_salary" value="{{ old('daily_salary') }}" class="form-control @error('daily_salary') is-invalid @enderror" required min="0">
                    @error('daily_salary')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input type="hidden" name="status" value="inactive">
                        <input id="status" type="checkbox" name="status" value="active" class="form-check-input" {{ old('status', 'active') === 'active' ? 'checked' : '' }}>
                        <label for="status" class="form-check-label">Karyawan Aktif</label>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
