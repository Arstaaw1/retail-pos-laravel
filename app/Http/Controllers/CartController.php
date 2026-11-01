<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Stok produk habis!');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] < $product->stock) {
                $cart[$id]['quantity']++;
            } else {
                return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia!');
            }
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->selling_price ?? $product->price,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang!');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan!');
    }
}