@extends('layouts.app')

@section('content')
<!-- Chart.js untuk Grafik Penjualan Owner -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .stat-card { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .btn-shortcut { border-radius: 10px; padding: 15px; text-align: left; transition: all 0.2s; text-decoration: none; }
    .btn-shortcut:hover { transform: translateY(-3px); }
</style>

<div class="container-fluid px-4 py-3">

    {{-- ========================================== --}}
    {{-- TAMPILAN KHUSUS OWNER / ADMIN              --}}
    {{-- ========================================== --}}
    @if(in_array(auth()->user()->role ?? '', ['owner', 'admin']))
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0 text-dark">Panel Analisis Owner</h4>
                <small class="text-muted">Laporan performa penjualan dan statistik bisnis.</small>
            </div>
        </div>

        <!-- RINGKASAN CARDS OWNER -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card stat-card bg-primary text-white p-3">
                    <small class="text-white-50 text-uppercase fw-bold">Total Omset Hari Ini</small>
                    <h3 class="fw-bold mb-0 mt-1">Rp {{ number_format($todayRevenue ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-success text-white p-3">
                    <small class="text-white-50 text-uppercase fw-bold">Transaksi Selesai</small>
                    <h3 class="fw-bold mb-0 mt-1">{{ $todayTransactionsCount ?? 0 }} Transaksi</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-warning text-dark p-3">
                    <small class="text-dark-50 text-uppercase fw-bold">Perlu Restok</small>
                    <h3 class="fw-bold mb-0 mt-1">{{ isset($lowStockProducts) ? $lowStockProducts->count() : 0 }} Produk</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-danger text-white p-3">
                    <small class="text-white-50 text-uppercase fw-bold">Req. Pembatalan</small>
                    <h3 class="fw-bold mb-0 mt-1">{{ $pendingCancelsCount ?? 0 }} Pending</h3>
                </div>
            </div>
        </div>

        <!-- GRAFIK PENJUALAN & TOP PRODUK -->
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <h6 class="fw-bold mb-3"><i class="bi bi-graph-up text-primary me-2"></i>Grafik Penjualan 7 Hari Terakhir</h6>
                    <canvas id="salesChart" height="120"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <h6 class="fw-bold mb-3"><i class="bi bi-trophy text-warning me-2"></i>Produk Terlaris</h6>
                    <ul class="list-group list-group-flush">
                        @forelse($topProducts ?? [] as $top)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="fw-semibold">{{ $top->name }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $top->total_sold }} pcs</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center py-3">Belum ada data</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- AKSES CEPAT (SHORTCUT MANAGEMENT) -->
        <h6 class="fw-bold mb-3">Akses Cepat Master Data</h6>
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('sales.index') }}" class="btn btn-outline-primary w-100 btn-shortcut d-flex align-items-center gap-3">
                    <i class="bi bi-receipt fs-2"></i>
                    <div><strong class="d-block">Kelola Penjualan</strong><small class="text-muted">Cek & Batal Transaksi</small></div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('products.index') }}" class="btn btn-outline-success w-100 btn-shortcut d-flex align-items-center gap-3">
                    <i class="bi bi-box-seam fs-2"></i>
                    <div><strong class="d-block">Master Produk</strong><small class="text-muted">Kelola Katalog Produk</small></div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('categories.index') }}" class="btn btn-outline-warning w-100 btn-shortcut d-flex align-items-center gap-3 text-dark">
                    <i class="bi bi-tags fs-2"></i>
                    <div><strong class="d-block">Master Kategori</strong><small class="text-muted">Kelola Kelompok</small></div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="javascript:void(0)" onclick="alert('Hak Akses User dikelola secara otomatis oleh Sistem (Seeder).')" class="btn btn-outline-dark w-100 btn-shortcut d-flex align-items-center gap-3">
                    <i class="bi bi-people fs-2"></i>
                    <div><strong class="d-block">Kelola User</strong><small class="text-muted">Staff & Customer</small></div>
                </a>
            </div>
        </div>

    {{-- ========================================== --}}
    {{-- TAMPILAN KHUSUS STAFF / KASIR              --}}
    {{-- ========================================== --}}
    @else
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0 text-dark">Panel Operasional Kasir</h4>
                <small class="text-muted">Fokus ke pelayanan transaksi & monitoring stok.</small>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <a href="{{ route('products.index') }}" class="btn btn-primary p-4 w-100 text-start rounded-3 shadow-sm d-flex justify-content-between align-items-center text-decoration-none">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="bi bi-cart-plus me-2"></i> Melayani Transaksi Baru</h4>
                        <p class="mb-0 opacity-75">Buka Data Produk untuk proses Pembelian Pelanggan</p>
                    </div>
                    <i class="bi bi-arrow-right-circle fs-1"></i>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('sales.index') }}" class="btn btn-dark p-4 w-100 text-start rounded-3 shadow-sm d-flex justify-content-between align-items-center text-decoration-none">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="bi bi-clock-history me-2"></i> Riwayat Transaksi Kasir</h4>
                        <p class="mb-0 opacity-75">Lihat daftar transaksi yang telah dibuat</p>
                    </div>
                    <i class="bi bi-arrow-right-circle fs-1"></i>
                </a>
            </div>
        </div>

        <!-- TABEL STOK MENIPIS UNTUK STAFF -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Daftar Produk Perlu Restok</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>SKU</th>
                            <th>Nama Produk</th>
                            <th class="text-center">Sisa Stok</th>
                            <th class="text-center">Minimum Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockProducts ?? [] as $prod)
                            <tr>
                                <td><code>{{ $prod->sku }}</code></td>
                                <td class="fw-semibold">{{ $prod->name }}</td>
                                <td class="text-center"><span class="badge bg-danger">{{ $prod->stock }}</span></td>
                                <td class="text-center text-muted">{{ $prod->minimum_stock }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Stok barang dalam kondisi aman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

<!-- SCRIPT CHART.JS UNTUK GRAFIK OWNER -->
@if(in_array(auth()->user()->role ?? '', ['owner', 'admin']))
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('salesChart');
        if (ctx) {
            new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                    datasets: [{
                        label: 'Penjualan (Rp)',
                        data: [1200000, 1900000, 3000000, 500000, 2000000, 3500000, {{ $todayRevenue ?? 0 }}],
                        backgroundColor: '#2563eb',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } }
                }
            });
        }
    });
</script>
@endif
@endsection