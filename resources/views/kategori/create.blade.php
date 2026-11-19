@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}" class="text-decoration-none">Kategori</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
            </ol>
        </nav>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                        <i class="fa-solid fa-plus text-primary"></i>
                    </div>
                    <h5 class="mb-0 fw-bold">Tambah Kategori Baru</h5>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary small text-uppercase">Nama Kategori</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fa-solid fa-tag text-muted"></i>
                            </span>
                            <input type="text" name="nama_kategori" 
                                   class="form-control bg-light border-start-0 @error('nama_kategori') is-invalid @enderror" 
                                   placeholder="Contoh: Makanan, Elektronik, dll"
                                   value="{{ old('nama_kategori') }}"
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
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-4 p-3 bg-primary bg-opacity-10 rounded-3 border border-primary border-opacity-10">
            <p class="mb-0 small text-primary">
                <i class="fa-solid fa-circle-info me-2"></i> 
                <strong>Tips:</strong> Pastikan nama kategori belum pernah terdaftar sebelumnya untuk menghindari duplikasi data.
            </p>
        </div>
    </div>
</div>

<style>
    /* Memberikan efek fokus yang lebih modern pada input group */
    .input-group:focus-within .input-group-text {
        border-color: #4e73df;
        color: #4e73df !important;
    }
    .form-control:focus {
        background-color: #fff !important;
        box-shadow: none;
    }
    .card {
        transition: transform 0.3s ease;
    }
</style>
@endsection