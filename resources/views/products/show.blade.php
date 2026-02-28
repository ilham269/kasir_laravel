@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Detail Produk</h4>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <strong>Gambar Produk:</strong>
                <div class="mt-2">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar produk" style="max-width: 220px; height: auto;">
                    @else
                        -
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <strong>Nama Produk:</strong>
                <div>{{ $product->name }}</div>
            </div>

            <div class="mb-3">
                <strong>Kategori:</strong>
                <div>{{ optional($product->category)->name ?: '-' }}</div>
            </div>

            <div class="mb-3">
                <strong>Barcode:</strong>
                <div>{{ $product->barcode ?: '-' }}</div>
            </div>

            <div class="mb-3">
                <strong>Harga Jual:</strong>
                <div>Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            </div>

            <div class="mb-3">
                <strong>Harga Modal:</strong>
                <div>
                    @if (is_null($product->cost_price))
                        -
                    @else
                        Rp {{ number_format($product->cost_price, 0, ',', '.') }}
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <strong>Stok:</strong>
                <div>{{ $product->stock }}</div>
            </div>

            <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>
</div>
@endsection
