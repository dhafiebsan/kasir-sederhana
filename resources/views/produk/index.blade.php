@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-0">Stok Barang / Produk</h4>
        <p class="text-muted small mb-0">Kelola ketersediaan barang, satuan, dan harga jual.</p>
    </div>
    <a href="{{ route('produk.create') }}" class="btn btn-primary px-4 shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Tambah Produk
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
                        <th class="ps-4 py-3" width="60px">No</th>
                        <th class="py-3">Nama Produk</th>
                        <th class="py-3">Kategori</th>
                        <th class="py-3">Harga</th>
                        <th class="py-3">Stok</th>
                        <th class="py-3">Satuan</th>
                        <th class="py-3 text-center" width="150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($produk->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data produk.</td>
                        </tr>
                    @else
                        @foreach($produk as $item)
                        <tr>
                            <td class="ps-4 text-secondary">{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-bold text-dark">{{ $item->nama_produk }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-primary border border-primary border-opacity-25 px-3 py-2">
                                    {{ $item->kategori->nama_kategori }}
                                </span>
                            </td>
                            <td class="fw-semibold">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($item->stok == 0)
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">
                                        <i class="fa-solid fa-circle-xmark me-1"></i> Habis (0)
                                    </span>
                                @elseif($item->stok <= 10)
                                    <span class="badge bg-warning bg-opacity-25 text-dark border border-warning border-opacity-50 px-3 py-2">
                                        <i class="fa-solid fa-triangle-exclamation text-warning me-1"></i> {{ $item->stok }}
                                    </span>
                                @else
                                    <span class="text-dark fw-semibold ps-2">{{ $item->stok }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary text-uppercase px-2 py-1">
                                    {{ $item->satuan ?? 'pcs' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('produk.edit', $item->id) }}" 
                                       class="btn btn-sm btn-outline-warning border-0 mx-1" 
                                       title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    
                                    <form action="{{ route('produk.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 mx-1" 
                                                onclick="return confirm('Yakin ingin menghapus produk ini?')" 
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
    .table thead th {
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-weight: 700;
        color: #6c757d;
    }
    .table tbody tr:hover {
        background-color: #fcfcfc;
    }
    .badge {
        font-weight: 600;
        border-radius: 6px;
    }
    .btn-outline-warning:hover { background-color: #fff4e5; color: #ff9800; }
    .btn-outline-danger:hover { background-color: #ffe5e5; color: #f44336; }
</style>
@endsection
