@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Detail Transaksi</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('cashier.transactions.receipt', $transaction) }}" target="_blank" class="btn btn-dark">Lihat Struk</a>
            <a href="{{ route('cashier.transactions.receipt.export', $transaction) }}" class="btn btn-success">Export Struk</a>
            <a href="{{ route('cashier.index') }}" class="btn btn-primary">Transaksi Baru</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div><strong>ID Transaksi:</strong> #{{ $transaction->id }}</div>
                    <div><strong>Invoice:</strong> {{ $transaction->invoice_code }}</div>
                    <div><strong>Kasir (User):</strong> {{ $transaction->user?->name ?? 'User tidak ditemukan' }}</div>
                </div>
                <div class="col-md-6">
                    <div><strong>Tanggal Transaksi:</strong> {{ $transaction->transaction_date?->format('d-m-Y H:i:s') }}</div>
                    <div><strong>Dibuat Pada:</strong> {{ $transaction->created_at?->format('d-m-Y H:i:s') }}</div>
                    <div><strong>Metode Bayar:</strong> {{ strtoupper($transaction->payment_method) }}</div>
                </div>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->details as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ optional($detail->product)->name ?: '-' }}</td>
                            <td>{{ optional($detail->product)->barcode ?: '-' }}</td>
                            <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Total Item</th>
                        <th>{{ $transaction->details->sum('quantity') }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">Total Belanja</th>
                        <th>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">Dibayar</th>
                        <th>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">Kembalian</th>
                        <th>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
