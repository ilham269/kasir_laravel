@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Halaman Kasir</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('cashier.add') }}" method="POST" class="row g-2">
                @csrf
                <div class="col-md-9">
                    <label for="barcode" class="form-label" autofocus>Input / Scan Barcode</label>
                    <input type="text" name="barcode" id="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode') }}" autofocus required>
                    @error('barcode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Tambah ke Keranjang</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h6 class="mb-3">Pilih Produk (Klik untuk tambah)</h6>
            <div class="row g-3">
                @forelse ($products as $product)
                    @php
                        $qtyInCart = $cartQuantities[$product->id] ?? 0;
                        $isOutOfStock = $product->stock <= 0;
                        $isMaxInCart = $qtyInCart >= $product->stock;
                    @endphp
                    <div class="col-md-3 col-sm-6">
                        <div class="card h-100">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="height: 140px; object-fit: cover;">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title mb-1">{{ $product->name }}</h6>
                                <div class="small text-muted mb-1">{{ optional($product->category)->name ?: 'Tanpa kategori' }}</div>
                                <div class="small mb-1">Barcode: {{ $product->barcode ?: '-' }}</div>
                                <div class="small mb-1">Stok: {{ $product->stock }}</div>
                                <div class="fw-bold mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                <form action="{{ route('cashier.add-product') }}" method="POST" class="mt-auto">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100" {{ ($isOutOfStock || $isMaxInCart) ? 'disabled' : '' }}>
                                        @if ($isOutOfStock)
                                            Stok Habis
                                        @elseif ($isMaxInCart)
                                            Stok Maksimal di Keranjang
                                        @else
                                            Tambah Produk
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning mb-0">Belum ada data produk.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th width="60">#</th>
                        <th>Produk</th>
                        <th>Barcode</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cart as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['barcode'] ?: '-' }}</td>
                            <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('cashier.remove', $item['product_id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3">Keranjang masih kosong.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Total</th>
                        <th colspan="2">Rp {{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if (count($cart) > 0)
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="mb-3">Pembayaran</h6>
                <form action="{{ route('cashier.pay') }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-md-4">
                        <label for="payment_method" class="form-label">Metode Bayar</label>
                        <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                            <option value="cash" {{ old('payment_method', 'cash') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="qris" {{ old('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="paid_amount" class="form-label">Jumlah Bayar</label>
                        <input type="number" step="0.01" min="{{ $total }}" name="paid_amount" id="paid_amount" class="form-control @error('paid_amount') is-invalid @enderror" value="{{ old('paid_amount', $total) }}" required>
                        @error('paid_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="change_amount_preview" class="form-label">Kembalian</label>
                        <input type="text" id="change_amount_preview" class="form-control" value="Rp 0" readonly>
                        <small id="change_note" class="text-muted">Masukkan jumlah bayar untuk menghitung kembalian.</small>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Bayar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const barcodeInput = document.getElementById('barcode');
        const paidInput = document.getElementById('paid_amount');
        const changePreview = document.getElementById('change_amount_preview');
        const changeNote = document.getElementById('change_note');
        const total = {{ (float) $total }};

        function formatRupiah(value) {
            return 'Rp ' + Number(value).toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        }

        function updateChange() {
            if (!paidInput || !changePreview || !changeNote) {
                return;
            }

            const paid = parseFloat(paidInput.value || 0);
            const change = paid - total;

            if (change >= 0) {
                changePreview.value = formatRupiah(change);
                changeNote.textContent = 'Kembalian siap diberikan ke customer.';
                changeNote.className = 'small text-success';
            } else {
                changePreview.value = formatRupiah(0);
                changeNote.textContent = 'Uang bayar kurang Rp ' + Number(Math.abs(change)).toLocaleString('id-ID');
                changeNote.className = 'small text-danger';
            }
        }

        if (barcodeInput) {
            barcodeInput.addEventListener('change', function () {
                this.form.submit();
            });
        }

        if (paidInput) {
            paidInput.addEventListener('input', updateChange);
            updateChange();
        }
    });
</script>
@endsection
