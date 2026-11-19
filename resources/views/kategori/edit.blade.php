@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}" class="text-decoration-none">Kategori</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
            </ol>
        </nav>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-2 rounded-3 me-3">
                        <i class="fa-solid fa-pen-to-square text-warning"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">Edit Kategori</h5>
                        <small class="text-muted">Mengubah data: <strong>{{ $kategori->nama_kategori }}</strong></small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary small text-uppercase">Nama Kategori Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fa-solid fa-tag text-muted"></i>
                            </span>
                            <input type="text" name="nama_kategori" 
                                   class="form-control bg-light border-start-0 @error('nama_kategori') is-invalid @enderror" 
                                   value="{{ old('nama_kategori', $kategori->nama_kategori) }}" 
                                   required>
                        </div>
                        @error('nama_kategori')
                            <div class="text-danger small mt-2">
                                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <a href="{{ route('kategori.index') }}" class="btn btn-light px-4 fw-medium">
                            <i class="fa-solid fa-arrow-left me-2"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-warning px-5 fw-bold shadow-sm text-dark">
                            <i class="fa-solid fa-rotate me-2"></i> Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-4 p-3 bg-warning bg-opacity-10 rounded-3 border border-warning border-opacity-10">
            <p class="mb-0 small text-dark">
                <i class="fa-solid fa-triangle-exclamation me-2 text-warning"></i> 
                <strong>Perhatian:</strong> Perubahan nama kategori akan langsung berdampak pada seluruh produk yang terhubung dengan kategori ini.
            </p>
        </div>
    </div>
</div>

<style>
    .input-group:focus-within .input-group-text {
        border-color: #ffc107;
        color: #ffc107 !important;
    }
    .form-control:focus {
        background-color: #fff !important;
        box-shadow: none;
        border-color: #ffc107;
    }
</style>
@endsection