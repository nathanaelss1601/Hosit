@extends('layouts.app')

@section('title', 'POS')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-cart-check"></i> Point of Sale (POS)</h2>
        <p class="text-muted mb-0">Pilih produk, atur pembayaran, lalu simpan transaksi.</p>
    </div>
    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-receipt"></i> Lihat Transaksi
    </a>
</div>

@if($products->isEmpty())
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i> Belum ada produk aktif. Tambahkan produk terlebih dahulu.
    </div>
@else
    <form action="{{ route('transactions.create-from-pos') }}" method="POST" id="pos-form">
        @csrf

        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-check"></i> Pilih Item
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Pilih</th>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $index => $product)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input item-check" data-index="{{ $index }}" data-price="{{ (float) $product->price }}">
                                        <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $product->id }}" id="product-{{ $index }}" disabled>
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? '-' }}</td>
                                    <td class="fw-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td style="max-width: 130px;">
                                        <input type="number" min="1" value="1" class="form-control qty-input" name="items[{{ $index }}][quantity]" id="qty-{{ $index }}" disabled>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle"></i> Pendapatan Custom (Tanpa Produk)
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="custom_description" class="form-label">Deskripsi (opsional)</label>
                        <input type="text" id="custom_description" name="custom_description" class="form-control" placeholder="Contoh: Service, Tip, dll">
                    </div>
                    <div class="col-md-6">
                        <label for="custom_amount" class="form-label">Jumlah (Rp)</label>
                        <input type="number" step="0.01" min="0" id="custom_amount" name="custom_amount" class="form-control" value="0" placeholder="0">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-credit-card"></i> Data Pembayaran
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="customer_name" class="form-label">Nama Pelanggan (opsional)</label>
                        <input type="text" id="customer_name" name="customer_name" class="form-control" value="{{ old('customer_name') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="payment_method" class="form-label">Metode Pembayaran</label>
                        <select id="payment_method" name="payment_method" class="form-select" required>
                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                            <option value="qris" {{ old('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="discount" class="form-label">Diskon (Rp)</label>
                        <input type="number" step="0.01" min="0" id="discount" name="discount" class="form-control" value="{{ old('discount', 0) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Estimasi Total</label>
                        <div class="form-control bg-light" id="estimated-total">Rp 0</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Total Setelah Diskon</label>
                        <div class="form-control bg-light" id="final-total">Rp 0</div>
                    </div>
                    <div class="col-md-3">
                        <label for="payment_amount" class="form-label">Jumlah Bayar</label>
                        <input type="number" step="0.01" min="0" id="payment_amount" name="payment_amount" class="form-control @error('payment_amount') is-invalid @enderror" value="{{ old('payment_amount', 0) }}" required>
                        <div id="payment-error" class="invalid-feedback d-none">
                            Jumlah bayar lebih rendah dari total transaksi!
                        </div>
                        @error('payment_amount')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="notes" class="form-label">Catatan (opsional)</label>
                        <textarea id="notes" name="notes" rows="2" class="form-control">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div id="pos-submit-error" class="alert alert-danger d-none mt-3" role="alert">
            <i class="bi bi-exclamation-circle"></i> <span id="pos-submit-error-text"></span>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="bi bi-check-circle"></i> Simpan Transaksi
        </button>
    </form>
@endif
@endsection

@push('scripts')
<script>
    (function () {
        const checks = document.querySelectorAll('.item-check');
        const totalEl = document.getElementById('estimated-total');
        const finalTotalEl = document.getElementById('final-total');
        const customAmountInput = document.getElementById('custom_amount');
        const discountInput = document.getElementById('discount');
        const paymentAmountInput = document.getElementById('payment_amount');
        const paymentError = document.getElementById('payment-error');
        const posForm = document.getElementById('pos-form');
        const submitError = document.getElementById('pos-submit-error');
        const submitErrorText = document.getElementById('pos-submit-error-text');

        function showSubmitError(message) {
            submitErrorText.textContent = message;
            submitError.classList.remove('d-none');
        }

        function hideSubmitError() {
            submitError.classList.add('d-none');
            submitErrorText.textContent = '';
        }

        function hasSelectedItems() {
            const hasProducts = Array.from(checks).some((checkbox) => checkbox.checked);
            const hasCustomIncome = Number(customAmountInput.value || 0) > 0;
            return hasProducts || hasCustomIncome;
        }

        function getFinalTotal() {
            let total = 0;

            checks.forEach((checkbox) => {
                if (!checkbox.checked) {
                    return;
                }

                const idx = checkbox.dataset.index;
                const price = Number(checkbox.dataset.price || 0);
                const qty = Number(document.getElementById('qty-' + idx).value || 0);
                total += price * qty;
            });

            total += Number(customAmountInput.value || 0);
            total -= Number(discountInput.value || 0);

            return Math.max(0, total);
        }

        function showPaymentError(show) {
            if (show) {
                paymentAmountInput.classList.add('is-invalid');
                paymentError.classList.remove('d-none');
                paymentError.classList.add('d-block');
            } else {
                paymentAmountInput.classList.remove('is-invalid');
                paymentError.classList.add('d-none');
                paymentError.classList.remove('d-block');
            }
        }

        function validatePaymentAmount() {
            const finalTotal = getFinalTotal();
            const paymentAmount = Number(paymentAmountInput.value || 0);

            if (finalTotal <= 0) {
                showPaymentError(false);
                return true;
            }

            const isInvalid = paymentAmount < finalTotal;
            showPaymentError(isInvalid);
            return !isInvalid;
        }

        function formatRupiah(number) {
            return 'Rp ' + Number(number).toLocaleString('id-ID');
        }

        function recalcTotal() {
            let total = 0;
            
            // Add product totals
            checks.forEach((checkbox) => {
                if (!checkbox.checked) {
                    return;
                }

                const idx = checkbox.dataset.index;
                const price = Number(checkbox.dataset.price || 0);
                const qty = Number(document.getElementById('qty-' + idx).value || 0);
                total += price * qty;
            });

            // Add custom income
            const customAmount = Number(customAmountInput.value || 0);
            total += customAmount;

            // Calculate final total after discount
            const discount = Number(discountInput.value || 0);
            const finalTotal = total - discount;

            totalEl.textContent = formatRupiah(total);
            finalTotalEl.textContent = formatRupiah(Math.max(0, finalTotal));
            validatePaymentAmount();
        }

        checks.forEach((checkbox) => {
            checkbox.addEventListener('change', function () {
                const idx = this.dataset.index;
                const qtyInput = document.getElementById('qty-' + idx);
                const productInput = document.getElementById('product-' + idx);

                qtyInput.disabled = !this.checked;
                productInput.disabled = !this.checked;

                recalcTotal();
            });
        });

        document.querySelectorAll('.qty-input').forEach((input) => {
            input.addEventListener('input', recalcTotal);
        });

        customAmountInput.addEventListener('input', recalcTotal);
        discountInput.addEventListener('input', recalcTotal);
        paymentAmountInput.addEventListener('input', validatePaymentAmount);

        posForm.addEventListener('submit', function (event) {
            hideSubmitError();

            if (!hasSelectedItems()) {
                event.preventDefault();
                showSubmitError('Pilih setidaknya satu produk atau masukkan pendapatan custom!');
                return;
            }

            if (!validatePaymentAmount()) {
                event.preventDefault();
                showSubmitError('Jumlah bayar lebih rendah dari total transaksi!');
                paymentAmountInput.focus();
            }
        });

        recalcTotal();
    })();
</script>
@endpush
