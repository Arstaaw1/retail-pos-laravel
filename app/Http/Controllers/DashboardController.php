<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 0. Cek Role: Jika Customer, langsung alihkan ke Katalog Produk
        if (Auth::user()->role === 'customer') {
            return redirect()->route('home');
        }

        // 1. Total Produk di Toko
        $totalProducts = Product::count();

        // 2. Total Pemasukan Hari Ini (hanya transaksi completed)
        $todayRevenue = Sale::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total');

        // 3. Total Transaksi Selesai Hari Ini
        $todayTransactionsCount = Sale::whereDate('created_at', today())
            ->where('status', 'completed')
            ->count();

        // 4. Produk Stok Menipis (stok <= minimum_stock)
        $lowStockProducts = Product::whereColumn('stock', '<=', 'minimum_stock')->get();

        // 5. Permohonan Pembatalan yang Pending
        $pendingCancelsCount = Sale::where('status', 'pending_cancellation')->count();

        // 6. Top 5 Produk Terlaris
        $topProducts = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('sale', function ($query) {
                $query->where('status', 'completed');
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'name' => $item->product->name ?? 'Produk Dihapus',
                    'total_sold' => $item->total_sold,
                ];
            });

        return view('dashboard', compact(
            'totalProducts',
            'todayRevenue',
            'todayTransactionsCount',
            'lowStockProducts',
            'pendingCancelsCount',
            'topProducts'
        ));
    }
}