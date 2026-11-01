@extends('layouts.app')

@section('content')
<style>
    .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
</style>

<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark">Riwayat Transaksi Penjualan</h4>
            <small class="text-muted">Pantau status pesanan dan kelola pengajuan pembatalan pelanggan.</small>
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

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
        </div>
    @endif

    <div class="card card-custom bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th class="ps-4">No. Transaksi</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Detail Item</th>
                            <th>Total Bayar</th>
                            <th class="text-center">Status</th>
                            <th class="text-center pe-4">Aksi Pembatalan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td class="ps-4">
                                    <code class="fw-bold text-dark fs-6">{{ $sale->sale_number }}</code>
                                </td>
                                <td class="small text-muted">
                                    {{ $sale->created_at ? $sale->created_at->format('d M Y H:i') : '-' }}
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $sale->user->name ?? 'Pelanggan' }}</span>
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0 small">
                                        @foreach($sale->items as $item)
                                            <li>
                                                <i class="bi bi-dot text-primary"></i>
                                                {{ $item->product->name ?? 'Produk' }} 
                                                <span class="badge bg-light text-dark border ms-1">{{ $item->quantity }}x</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($sale->total, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @if($sale->status === 'completed')
                                        <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle me-1"></i> Selesai</span>
                                    @elseif($sale->status === 'pending_cancellation' || $sale->status === 'cancel_pending')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-hourglass-split me-1"></i> Minta Batal</span>
                                    @elseif($sale->status === 'cancelled')
                                        <span class="badge bg-danger rounded-pill px-3 py-2"><i class="bi bi-x-circle me-1"></i> Dibatalkan</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    {{-- 1. OPSI UNTUK PELANGGAN (CUSTOMER) --}}
                                    @if(auth()->user()->role === 'customer')
                                        @if($sale->status === 'completed')
                                            <form action="{{ route('sales.requestCancel', $sale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ajukan pembatalan untuk transaksi ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning btn-sm fw-semibold">
                                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Ajukan Batal
                                                </button>
                                            </form>
                                        @elseif($sale->status === 'pending_cancellation' || $sale->status === 'cancel_pending')
                                            <small class="text-muted fst-italic">Menunggu Persetujuan Owner</small>
                                        @else
                                            <small class="text-muted">Transaksi Batal</small>
                                        @endif
                                    @endif

                                    {{-- 2. OPSI UNTUK OWNER / ADMIN / STAFF --}}
                                    @if(in_array(auth()->user()->role, ['admin', 'owner', 'staff']))
                                        @if($sale->status === 'pending_cancellation' || $sale->status === 'cancel_pending')
                                            @if(in_array(auth()->user()->role, ['admin', 'owner']))
                                                <form action="{{ route('sales.approveCancel', $sale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Setujui pembatalan dan kembalikan stok barang?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm fw-bold">
                                                        <i class="bi bi-check2-circle me-1"></i> Setujui Batal
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-secondary">Khusus Owner/Admin</span>
                                            @endif
                                        @elseif($sale->status === 'completed')
                                            <small class="text-muted">Transaksi Selesai</small>
                                        @else
                                            <small class="text-muted">Stok Sudah Dikembalikan</small>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-receipt fs-1 d-block mb-2 opacity-50"></i>
                                    Belum ada riwayat transaksi penjualan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection