@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container py-4">

    <h3 class="mb-4">📊 Laporan Penjualan</h3>

    {{-- FILTER --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}">
                <div class="row">

                    <div class="col-md-4">
                        <label>Tanggal (Harian)</label>
                        <input type="date" name="date" class="form-control"
                               value="{{ $selectedDate }}">
                    </div>

                    <div class="col-md-4">
                        <label>Bulan</label>
                        <input type="month" name="month" class="form-control"
                               value="{{ $selectedMonth }}">
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-primary w-100">
                            Filter
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- SUMMARY --}}
    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Total Harian</h6>
                    <h4>Rp {{ number_format($dailyTotal, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Total Bulanan</h6>
                    <h4>Rp {{ number_format($monthlyTotal, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h6>Total Keseluruhan</h6>
                    <h4>Rp {{ number_format($allTimeTotal, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

    </div>

    {{-- TRANSAKSI HARIAN --}}
    <div class="card mb-4">
        <div class="card-header">
            Transaksi Harian ({{ $selectedDate }})
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Kasir</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyTransactions as $trx)
                        <tr>
                            <td>{{ $trx->invoice_code }}</td>
                            <td>{{ $trx->user->name ?? '-' }}</td>
                            <td>Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td>{{ $trx->transaction_date }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TRANSAKSI BULANAN --}}
    <div class="card mb-4">
        <div class="card-header">
            Transaksi Bulanan ({{ $selectedMonth }})
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Kasir</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthlyTransactions as $trx)
                        <tr>
                            <td>{{ $trx->invoice_code }}</td>
                            <td>{{ $trx->user->name ?? '-' }}</td>
                            <td>Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td>{{ $trx->transaction_date }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PRODUK TERLARIS --}}
    <div class="card">
        <div class="card-header">
            🔥 Produk Terlaris Bulan Ini
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Total Terjual</th>
                        <th>Total Penjualan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bestSellingProducts as $product)
                        <tr>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->total_qty }}</td>
                            <td>Rp {{ number_format($product->total_sales, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
