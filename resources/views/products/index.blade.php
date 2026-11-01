@extends('layouts.app')

@section('content')
<style>
    .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .product-img { width: 45px; height: 45px; object-fit: cover; border-radius: 8px; }
</style>

<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark">Manajemen Master Produk</h4>
            <small class="text-muted">Kelola data item, harga jual, stok, dan kategori barang.</small>
        </div>
        <button type="button" class="btn btn-warning fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card card-custom bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th class="ps-4">Produk</th>
                            <th>Kategori</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th class="text-center pe-4" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        @php
                                            $imgUrl = null;
                                            if ($product->image) {
                                                if (str_starts_with($product->image, 'http')) {
                                                    $imgUrl = $product->image;
                                                } else {
                                                    $imgUrl = \Illuminate\Support\Facades\Storage::url($product->image);
                                                }
                                            }
                                        @endphp

                                        @if($imgUrl)
                                            <img src="{{ $imgUrl }}" class="product-img" alt="{{ $product->name }}" 
                                                 onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                            <div class="product-img bg-light d-flex align-items-center justify-content-center text-secondary d-none">
                                                <i class="bi bi-box-seam fs-4"></i>
                                            </div>
                                        @else
                                            <div class="product-img bg-light d-flex align-items-center justify-content-center text-secondary">
                                                <i class="bi bi-box-seam fs-4"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <strong class="d-block text-dark">{{ $product->name }}</strong>
                                            <small class="text-muted">SKU: {{ $product->sku ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $product->category->name ?? 'Umum' }}</span>
                                </td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}
                                </td>
                                <td>
                                    <span class="badge {{ $product->stock > 5 ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger' }} px-2 py-1">
                                        {{ $product->stock }} unit
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <button type="button" class="btn btn-outline-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>

                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- MODAL EDIT PRODUK -->
                            <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Edit Produk</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Nama Produk</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Kategori</label>
                                                        <select name="category_id" class="form-select" required>
                                                            @foreach($categories as $cat)
                                                                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Harga Jual (Rp)</label>
                                                        <input type="number" name="selling_price" class="form-control" value="{{ $product->selling_price }}" min="0" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Stok</label>
                                                        <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" min="0" required>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-semibold">Pilih Foto Produk Baru (Opsional)</label>
                                                        <input type="file" name="image" class="form-control" accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning fw-bold">Update Produk</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-box-seam fs-1 d-block mb-2 opacity-50"></i>
                                    Belum ada data produk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH PRODUK -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Produk Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Produk</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Pulpen Gel Hitam" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga Jual (Rp)</label>
                            <input type="number" name="selling_price" class="form-control" placeholder="0" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok Awalan</label>
                            <input type="number" name="stock" class="form-control" placeholder="0" min="0" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Foto Produk (Opsional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection