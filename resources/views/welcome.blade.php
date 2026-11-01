@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark">Katalog Belanja</h4>
            <small class="text-muted">Pilih produk favoritmu dan tambahkan ke keranjang belanja.</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">
        @forelse($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    @if($product->image)
                        <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" 
                             class="card-img-top" 
                             alt="{{ $product->name }}" 
                             style="height: 160px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 160px;">
                            <i class="bi bi-box-seam fs-1"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column p-3">
                        <span class="badge bg-info text-dark align-self-start mb-2" style="font-size: 0.75rem;">
                            {{ $product->category->name ?? 'Umum' }}
                        </span>
                        <h6 class="card-title fw-bold text-dark mb-1">{{ $product->name }}</h6>
                        <p class="card-text fw-bold text-primary mb-2">
                            Rp {{ number_format($product->selling_price ?? $product->price ?? 0, 0, ',', '.') }}
                        </p>

                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <small class="text-muted">Stok: <strong>{{ $product->stock }}</strong></small>

                            @if($product->stock > 0)
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary fw-bold">
                                        <i class="bi bi-cart-plus me-1"></i> Beli
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-secondary" disabled>Habis</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-bag-x fs-1 d-block mb-2"></i>
                    Belum ada produk yang tersedia saat ini.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection