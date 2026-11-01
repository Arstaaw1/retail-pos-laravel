@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark">Manajemen Pemasok (Supplier)</h4>
            <small class="text-muted">Kelola data vendor/supplier barang retail.</small>
        </div>
        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pemasok
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Pemasok</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $index => $supplier)
                            <tr>
                                <td class="ps-4">{{ $index + 1 }}</td>
                                <td class="fw-semibold text-dark">{{ $supplier->name }}</td>
                                <td>{{ $supplier->phone ?? '-' }}</td>
                                <td>{{ $supplier->address ?? '-' }}</td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal" data-bs-target="#editSupplierModal{{ $supplier->id }}">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pemasok ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- MODAL EDIT PEMASOK --}}
                            <div class="modal fade" id="editSupplierModal{{ $supplier->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Edit Pemasok</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Nama Pemasok</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">No. Telepon</label>
                                                    <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Alamat</label>
                                                    <textarea name="address" class="form-control" rows="3">{{ $supplier->address }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary fw-bold">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada data pemasok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH PEMASOK BARU --}}
    <div class="modal fade" id="createSupplierModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Pemasok Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Pemasok</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: PT Indofood Sukses Makmur" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">No. Telepon</label>
                            <input type="text" name="phone" class="form-control" placeholder="081234567890">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Masukkan alamat lengkap supplier..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-bold">Simpan Pemasok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection