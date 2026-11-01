<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retail POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', system-ui, sans-serif; min-height: 100vh; }
        .sidebar { width: 250px; position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; background-color: #212529; }
        .main-content { margin-left: 250px; padding: 20px; }
        .nav-link { color: #adb5bd; padding: 12px 20px; font-weight: 500; border-radius: 6px; margin: 2px 10px; }
        .nav-link:hover, .nav-link.active { color: #fff; background-color: #0d6efd; }
        .nav-link i { font-size: 1.2rem; }
    </style>
</head>
<body>

    <!-- SIDEBAR KIRI -->
    <div class="sidebar d-flex flex-column justify-content-between py-3">
        <div>
            <div class="px-4 mb-4 text-white">
                <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-cart-check-fill me-2"></i>RETAIL POS</h5>
                <small class="text-muted">Sistem Kasir & Stok</small>
            </div>
            
            <ul class="nav nav-pills flex-column">
                @auth
                    {{-- 1. MENU KHUSUS CUSTOMER / PELANGGAN --}}
                    @if(auth()->user()->role === 'customer')
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                                <i class="bi bi-shop me-2"></i> Katalog Belanja
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cart.index') }}" class="nav-link {{ request()->routeIs('cart.*') ? 'active' : '' }} d-flex align-items-center justify-content-between">
                                <span><i class="bi bi-cart3 me-2"></i> Keranjang</span>
                                @if(session('cart') && count(session('cart')) > 0)
                                    <span class="badge bg-danger rounded-pill">{{ count(session('cart')) }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sales.myOrders') }}" class="nav-link {{ request()->routeIs('sales.myOrders') ? 'active' : '' }}">
                                <i class="bi bi-bag-check me-2"></i> Pesanan Saya
                            </a>
                        </li>

                    {{-- 2. MENU KHUSUS ADMIN / OWNER --}}
                    @elseif(in_array(auth()->user()->role, ['admin', 'owner']))
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                                <i class="bi bi-box-seam me-2"></i> Master Produk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                <i class="bi bi-tags me-2"></i> Master Kategori
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                                <i class="bi bi-truck me-2"></i> Master Pemasok
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                                <i class="bi bi-receipt me-2"></i> Data Transaksi
                            </a>
                        </li>

                    {{-- 3. MENU KHUSUS STAFF / KASIR --}}
                    @elseif(auth()->user()->role === 'staff')
                        <li class="nav-item">
                            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                                <i class="bi bi-box-seam me-2"></i> Data Produk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                                <i class="bi bi-cart-plus me-2"></i> Transaksi Penjualan
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
        
        <!-- USER & LOGOUT -->
        <div class="px-3 border-top border-secondary pt-3 text-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <strong class="d-block text-truncate" style="max-width: 130px;">{{ auth()->user()->name ?? 'User' }}</strong>
                    <span class="badge bg-secondary text-capitalize">{{ auth()->user()->role ?? 'Staff' }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>