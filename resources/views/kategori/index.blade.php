@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-0">Daftar Kategori</h4>
        <p class="text-muted small mb-0">Kelola kelompok jenis produk untuk memudahkan transaksi.</p>
    </div>
    <a href="{{ route('kategori.create') }}" class="btn btn-primary px-4 shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Tambah Kategori
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3" width="80px">No</th>
                        <th class="py-3">Nama Kategori</th>
                        <th class="py-3 text-center" width="150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($kategori->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">Belum ada data kategori.</td>
                        </tr>
                    @else
                        @foreach($kategori as $item)
                        <tr>
                            <td class="ps-4 text-secondary">{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $item->nama_kategori }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('kategori.edit', $item->id) }}" 
                                       class="btn btn-sm btn-outline-warning border-0 mx-1" 
                                       title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    
                                    <form action="{{ route('kategori.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 mx-1" 
                                                onclick="return confirm('Yakin ingin menghapus kategori ini?')" 
                                                title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Styling tabel */
    .table thead th {
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-weight: 700;
        color: #6c757d;
    }
    .table tbody tr {
        transition: all 0.2s;
    }
    .table tbody tr:hover {
        background-color: #fcfcfc;
    }
    .btn-outline-warning:hover {
        background-color: #fff4e5;
        color: #ff9800;
    }
    .btn-outline-danger:hover {
        background-color: #ffe5e5;
        color: #f44336;
    }
</style>
@endsection
