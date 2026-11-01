@extends('layouts.app')

@section('content')
<style>
    .card-order { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
</style>

<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-bag-check me-2"></i>Pesanan Saya</h4>
            <small class="text-muted">Pantau status pesanan dan riwayat belanja Anda.</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">
        @forelse($sales as $sale)
            <div class="col-12">
                <div class="card card-order bg-white p-3">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <div>
                            <span class="badge bg-secondary me-2">No. TRX</span>
                            <code class="fw-bold text-dark fs-6">{{ $sale->sale_number }}</code>
                            <small class="text-muted ms-3">{{ $sale->created_at ? $sale->created_at->format('d M Y H:i') : '' }}</small>
                        </div>
                        <div>
                            @if($sale->status === 'completed')
                                <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle me-1"></i> Selesai</span>
                            @elseif($sale->status === 'pending_cancellation' || $sale->status === 'cancel_pending')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-hourglass-split me-1"></i> Minta Batal</span>
                            @elseif($sale->status === 'cancelled')
                                <span class="badge bg-danger rounded-pill px-3 py-2"><i class="bi bi-x-circle me-1"></i> Dibatalkan</span>
                            @endif
                        </div>
                    </div>

                    <!-- DETAIL ITEM -->
                    <div class="mb-3">
                        @foreach($sale->items as $item)
                            @php
                                $itemPrice = $item->price > 0 ? $item->price : ($item->unit_price > 0 ? $item->unit_price : ($item->product->selling_price ?? $item->product->price ?? 0));
                                $itemSubtotal = $item->subtotal > 0 ? $item->subtotal : ($item->quantity * $itemPrice);
                            @endphp
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-light p-2 rounded">
                                        <i class="bi bi-box-seam text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $item->product->name ?? 'Produk' }}</strong>
                                        <small class="text-muted">Jumlah: {{ $item->quantity }} x Rp {{ number_format($itemPrice, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                                <span class="fw-semibold">Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- FOOTER PESANAN -->
                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                        <div>
                            <small class="text-muted d-block">Total Pesanan:</small>
                            <h5 class="fw-bold text-danger mb-0">Rp {{ number_format($sale->total, 0, ',', '.') }}</h5>
                        </div>

                        <div>
                            @if($sale->status === 'completed')
                                <form action="{{ route('sales.requestCancel', $sale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ajukan pembatalan untuk pesanan ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                        <i class="bi bi-x-circle me-1"></i> Batalkan Pesanan
                                    </button>
                                </form>
                            @elseif($sale->status === 'pending_cancellation' || $sale->status === 'cancel_pending')
                                <small class="text-warning fw-semibold fst-italic">
                                    <i class="bi bi-clock me-1"></i> Pengajuan pembatalan sedang diproses Owner
                                </small>
                            @else
                                <small class="text-muted">Pesanan ini telah dibatalkan</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5 bg-white rounded-3 shadow-sm">
                <i class="bi bi-bag-x fs-1 d-block mb-2 opacity-50"></i>
                Belum ada riwayat pesanan.
            </div>
        @endforelse
    </div>
</div>
@endsection