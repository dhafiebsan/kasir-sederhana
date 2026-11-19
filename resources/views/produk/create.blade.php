@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('produk.index') }}" class="text-decoration-none">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
            </ol>
        </nav>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                        <i class="fa-solid fa-box-open text-primary"></i>
                    </div>
                    <h5 class="mb-0 fw-bold">Tambah Produk Baru</h5>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('produk.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Pilih Kategori</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-layer-group text-muted"></i>
                                </span>
                                <select name="kategori_id" class="form-select bg-light border-start-0 @error('kategori_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>-- Pilih Kategori --</option>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('kategori_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Nama Produk</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-tag text-muted"></i>
                                </span>
                                <input type="text" name="nama_produk" class="form-control bg-light border-start-0 @error('nama_produk') is-invalid @enderror" 
                                       placeholder="Nama barang..." value="{{ old('nama_produk') }}" required>
                            </div>
                            @error('nama_produk') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Harga Jual (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-money-bill-wave text-muted"></i>
                                </span>
                                <input type="number" name="harga" class="form-control bg-light border-start-0 @error('harga') is-invalid @enderror" 
                                       placeholder="0" value="{{ old('harga') }}" required>
                            </div>
                            @error('harga') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Stok Awal</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-cubes text-muted"></i>
                                </span>
                                <input type="number" name="stok" class="form-control bg-light border-start-0 @error('stok') is-invalid @enderror" 
                                       placeholder="0" value="{{ old('stok') }}" required>
                            </div>
                            @error('stok') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Satuan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-scale-balanced text-muted"></i>
                                </span>
                                <input type="text" name="satuan" list="satuanList" class="form-control bg-light border-start-0 @error('satuan') is-invalid @enderror" 
                                       placeholder="pcs, botol, kg, dll" value="{{ old('satuan', 'pcs') }}" required>
                                <datalist id="satuanList">
                                    <option value="pcs">
                                    <option value="botol">
                                    <option value="cup">
                                    <option value="porsi">
                                    <option value="kg">
                                    <option value="gram">
                                    <option value="box">
                                    <option value="bungkus">
                                    <option value="liter">
                                    <option value="pack">
                                </datalist>
                            </div>
                            @error('satuan') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('produk.index') }}" class="btn btn-light px-4 fw-medium">
                            <i class="fa-solid fa-arrow-left me-2"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
@endsection
