<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['user', 'items.product'])->latest()->get();
        return view('sales.index', compact('sales'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang belanja masih kosong!');
        }

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            foreach ($cart as $id => $details) {
                $totalAmount += $details['price'] * $details['quantity'];
            }

            // 1. Simpan Transaksi Utama
            $sale = Sale::create([
                'sale_number' => 'INV-' . date('YmdHis') . '-' . rand(100, 999),
                'user_id'     => Auth::id(),
                'total'       => $totalAmount,
                'status'      => 'completed',
                'sold_at'     => now(),
            ]);

            // 2. Simpan Item & Potong Stok
            foreach ($cart as $productId => $details) {
                $product = Product::lockForUpdate()->find($productId);

                if ($product) {
                    if ($product->stock < $details['quantity']) {
                        DB::rollBack();
                        return redirect()->back()->with('error', "Stok produk {$product->name} tidak mencukupi!");
                    }

                    $product->decrement('stock', $details['quantity']);

                    SaleItem::create([
                        'sale_id'    => $sale->id,
                        'product_id' => $productId,
                        'quantity'   => $details['quantity'],
                        'unit_price' => $details['price'],
                        'subtotal'   => $details['price'] * $details['quantity'],
                    ]);
                }
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('sales.myOrders')->with('success', 'Transaksi checkout berhasil dilakukan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses checkout: ' . $e->getMessage());
        }
    }

    public function myOrders()
    {
        $sales = Sale::with('items.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('sales.my_orders', compact('sales'));
    }

    // TAHAP 1: Customer Mengajukan Pembatalan (Stok Belum Berubah)
    public function requestCancel($sale)
    {
        $saleItem = Sale::where('user_id', Auth::id())->findOrFail($sale);

        if ($saleItem->status === 'cancelled') {
            return redirect()->back()->with('error', 'Pesanan ini sudah dibatalkan!');
        }

        if ($saleItem->status === 'pending_cancellation' || $saleItem->status === 'cancel_pending') {
            return redirect()->back()->with('error', 'Pengajuan pembatalan Anda sedang menunggu persetujuan Owner.');
        }

        $saleItem->update([
            'status' => 'pending_cancellation',
        ]);

        return redirect()->back()->with('success', 'Pengajuan pembatalan telah dikirim. Menunggu persetujuan Owner.');
    }

    // TAHAP 2: Owner/Admin Menyetujui Pembatalan (Stok Baru Dikembalikan)
    public function approveCancel($sale)
    {
        Gate::authorize('admin');

        DB::beginTransaction();

        try {
            $saleItem = Sale::with('items')->findOrFail($sale);

            if ($saleItem->status === 'cancelled') {
                return redirect()->back()->with('error', 'Pesanan ini sudah dibatalkan sebelumnya.');
            }

            // Kembalikan stok barang ke database
            foreach ($saleItem->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            $saleItem->update([
                'status' => 'cancelled',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Pembatalan transaksi disetujui. Stok produk telah dikembalikan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyetujui pembatalan: ' . $e->getMessage());
        }
    }
}