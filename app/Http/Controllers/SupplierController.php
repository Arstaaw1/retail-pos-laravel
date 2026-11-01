<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        Gate::authorize('admin');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Supplier::create($validated);
        return redirect()->route('suppliers.index')->with('success', 'Pemasok berhasil ditambahkan!');
    }

    public function update(Request $request, Supplier $supplier)
    {
        Gate::authorize('admin');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $supplier->update($validated);
        return redirect()->route('suppliers.index')->with('success', 'Pemasok berhasil diperbarui!');
    }

    public function destroy(Supplier $supplier)
    {
        Gate::authorize('admin');

        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Pemasok berhasil dihapus!');
    }
}