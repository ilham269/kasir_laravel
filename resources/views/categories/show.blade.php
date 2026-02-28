@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Detail Kategori</h4>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <strong>Gambar:</strong>
                <div class="mt-2">
                    @if ($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="Gambar kategori" style="max-width: 220px; height: auto;">
                    @else
                        -
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <strong>Nama:</strong>
                <div>{{ $category->name }}</div>
            </div>

            <div class="mb-3">
                <strong>Deskripsi:</strong>
                <div>{{ $category->description ?: '-' }}</div>
            </div>

            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>
</div>
@endsection
