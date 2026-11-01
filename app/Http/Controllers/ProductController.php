<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Halaman Home / Katalog untuk Pelanggan (Memakai view 'welcome')
     */
    public function home()
    {
        $products = Product::with('category')->latest()->get();
        return view('welcome', compact('products'));
    }

    /**
     * Halaman Utama Manajemen Produk (Admin / Owner / Kasir)
     */
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Simpan Produk Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $sellingPrice = $request->selling_price;
        $purchasePrice = $request->purchase_price ?? $sellingPrice;

        $data = [
            'sku'            => 'PRD-' . strtoupper(Str::random(6)),
            'name'           => $request->name,
            'category_id'    => $request->category_id,
            'purchase_price' => $purchasePrice,
            'selling_price'  => $sellingPrice,
            'stock'          => $request->stock,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Update Data Produk
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $sellingPrice = $request->selling_price;
        $purchasePrice = $request->purchase_price ?? $product->purchase_price ?? $sellingPrice;

        $data = [
            'name'           => $request->name,
            'category_id'    => $request->category_id,
            'purchase_price' => $purchasePrice,
            'selling_price'  => $sellingPrice,
            'stock'          => $request->stock,
        ];

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Hapus Produk
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}