@extends('layouts.app')

@section('content')
<style>
    .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
</style>

<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark">Manajemen Master Kategori</h4>
            <small class="text-muted">Kelola kelompok/kategori produk toko Anda.</small>
        </div>
        <button type="button" class="btn btn-warning fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card card-custom bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th class="ps-4" style="width: 80px;">No</th>
                            <th>Nama Kategori</th>
                            <th class="text-center pe-4" style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index =>$category)
                            <tr>
                                <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>
                                <td class="fw-bold text-dark">{{ $category->name }}</td>
                                <td class="text-center pe-4">
                                    <button type="button" class="btn btn-outline-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </button>
                                    
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit Kategori -->
                            <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('categories.update', $category->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Edit Kategori</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Nama Kategori</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning fw-bold">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-5">
                                    <i class="bi bi-tags fs-1 d-block mb-2 opacity-50"></i>
                                    Belum ada data kategori.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori</label>
                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama kategori" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection