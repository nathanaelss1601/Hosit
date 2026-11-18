<div class="card">
    <div class="card-header">
        <i class="bi bi-funnel"></i> Filter Periode
    </div>
    <div class="card-body">
        <form method="GET" action="{{ $action }}" class="report-date-filter-form">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="report_start_date" class="form-label">Tanggal Mulai</label>
                    <input
                        type="date"
                        id="report_start_date"
                        name="start_date"
                        value="{{ old('start_date', $startDate) }}"
                        max="{{ old('end_date', $endDate) }}"
                        class="form-control report-start-date @error('start_date') is-invalid @enderror"
                        required
                    >
                    @error('start_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="report_end_date" class="form-label">Tanggal Selesai</label>
                    <input
                        type="date"
                        id="report_end_date"
                        name="end_date"
                        value="{{ old('end_date', $endDate) }}"
                        min="{{ old('start_date', $startDate) }}"
                        class="form-control report-end-date @error('end_date') is-invalid @enderror"
                        required
                    >
                    @error('end_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i> Terapkan
                    </button>
                    <a href="{{ $resetRoute }}" class="btn btn-outline-secondary">Reset</a>
                </div>
                <div class="col-12">
                    <div class="report-date-error alert alert-danger d-none mb-0" role="alert">
                        <i class="bi bi-exclamation-circle"></i> Tanggal mulai tidak boleh lebih dari tanggal selesai.
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.querySelectorAll('.report-date-filter-form').forEach(function (form) {
                const startInput = form.querySelector('.report-start-date');
                const endInput = form.querySelector('.report-end-date');
                const errorBox = form.querySelector('.report-date-error');

                function syncDateLimits() {
                    if (startInput.value) {
                        endInput.min = startInput.value;
                    }

                    if (endInput.value) {
                        startInput.max = endInput.value;
                    }
                }

                function isDateRangeValid() {
                    if (!startInput.value || !endInput.value) {
                        return true;
                    }

                    return startInput.value <= endInput.value;
                }

                function showDateError(show) {
                    if (!errorBox) {
                        return;
                    }

                    errorBox.classList.toggle('d-none', !show);
                    startInput.classList.toggle('is-invalid', show);
                    endInput.classList.toggle('is-invalid', show);
                }

                function validateDateRange() {
                    const valid = isDateRangeValid();
                    showDateError(!valid);
                    return valid;
                }

                startInput.addEventListener('change', function () {
                    syncDateLimits();
                    validateDateRange();
                });

                endInput.addEventListener('change', function () {
                    syncDateLimits();
                    validateDateRange();
                });

                form.addEventListener('submit', function (event) {
                    syncDateLimits();

                    if (!validateDateRange()) {
                        event.preventDefault();
                    }
                });

                syncDateLimits();
            });
        </script>
    @endpush
@endonce
