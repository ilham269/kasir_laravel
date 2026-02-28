@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container py-4">
    <div class="row g-4 align-items-stretch">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h3 class="mb-2">Selamat Datang, {{ Auth::user()->name }}</h3>
                    <p class="text-muted mb-4">
                        Kelola transaksi, produk, kategori, dan laporan penjualan dari satu dashboard.
                    </p>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('cashier.index') }}" class="btn btn-primary">
                            Buka Kasir
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                            Kelola Produk
                        </a>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                            Kelola Kategori
                        </a>
                        <a href="{{ route('reports.index') }}" class="btn btn-outline-dark">
                            Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="mb-3">Informasi Akun</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><strong>Nama:</strong> {{ Auth::user()->name }}</li>
                        <li class="mb-2"><strong>Email:</strong> {{ Auth::user()->email }}</li>
                        <li><strong>Status:</strong> Aktif</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="mb-3">Tentang Sistem</h5>
                    <p class="text-muted mb-0">
                        Sistem Kasir Pintar membantu operasional toko agar transaksi lebih cepat,
                        stok lebih terpantau, dan laporan penjualan lebih akurat.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="mb-3">Panduan Singkat</h5>
                    <ol class="mb-0 ps-3">
                        <li>Buka menu Kasir untuk transaksi.</li>
                        <li>Tambah dan kelola data produk.</li>
                        <li>Cek laporan untuk monitoring penjualan.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
