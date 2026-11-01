@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark">Keranjang Belanja</h4>
            <small class="text-muted">Tinjau daftar belanjaan Anda sebelum melakukan transaksi.</small>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Lanjut Belanja
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(count($cart) > 0)
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase fs-7">
                            <tr>
                                <th class="ps-4">Produk</th>
                                <th>Harga Satuan</th>
                                <th class="text-center">Jumlah (QTY)</th>
                                <th>Subtotal</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($cart as $id => $details)
                                @php 
                                    $subtotal = $details['price'] * $details['quantity'];
                                    $total += $subtotal;
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            @if(!empty($details['image']))
                                                <img src="{{ Str::startsWith($details['image'], 'http') ? $details['image'] : asset('storage/' . $details['image']) }}" class="rounded me-3" style="width: 45px; height: 45px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center text-muted" style="width: 45px; height: 45px;">
                                                    <i class="bi bi-box-seam fs-5"></i>
                                                </div>
                                            @endif
                                            <span class="fw-bold text-dark">{{ $details['name'] }}</span>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                    <td class="text-center" style="width: 120px;">
                                        <input type="number" class="form-control form-control-sm text-center fw-bold" value="{{ $details['quantity'] }}" readonly>
                                    </td>
                                    <td class="fw-bold text-success">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('cart.remove', $id) }}" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="bi bi-trash me-1"></i> Kosongkan Keranjang
                    </button>
                </form>

                <div class="d-flex align-items-center">
                    <div class="me-4 text-end">
                        <small class="text-muted d-block">Total Pembayaran:</small>
                        <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                    </div>

                    <form action="{{ route('sales.store') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg fw-bold px-4">
                            <i class="bi bi-credit-card me-2"></i> Checkout Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-3 text-center py-5">
            <div class="card-body">
                <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                <h5 class="fw-bold mt-3">Keranjang Belanja Kosong</h5>
                <p class="text-muted">Kamu belum menambahkan produk apa pun ke keranjang.</p>
                <a href="{{ route('home') }}" class="btn btn-primary fw-bold mt-2">
                    Mulai Belanja Sekarang
                </a>
            </div>
        </div>
    @endif
</div>
@endsection