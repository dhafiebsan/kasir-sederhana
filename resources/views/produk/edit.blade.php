@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('produk.index') }}" class="text-decoration-none">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Produk</li>
            </ol>
        </nav>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-2 rounded-3 me-3">
                        <i class="fa-solid fa-pen-to-square text-warning"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">Edit Produk</h5>
                        <small class="text-muted">Mengubah data: <strong>{{ $produk->nama_produk }}</strong></small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('produk.update', $produk->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Kategori</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-layer-group text-muted"></i>
                                </span>
                                <select name="kategori_id" class="form-select bg-light border-start-0 @error('kategori_id') is-invalid @enderror" required>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->id }}" {{ old('kategori_id', $produk->kategori_id) == $k->id ? 'selected' : '' }}>
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
                                       value="{{ old('nama_produk', $produk->nama_produk) }}" required>
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
                                       value="{{ old('harga', $produk->harga) }}" required>
                            </div>
                            @error('harga') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-semibold text-secondary small text-uppercase">Stok Barang</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-cubes text-muted"></i>
                                </span>
                                <input type="number" name="stok" class="form-control bg-light border-start-0 @error('stok') is-invalid @enderror" 
                                       value="{{ old('stok', $produk->stok) }}" required>
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
                                       placeholder="pcs, botol, kg, dll" value="{{ old('satuan', $produk->satuan ?? 'pcs') }}" required>
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
                        <button type="submit" class="btn btn-warning px-5 fw-bold shadow-sm text-dark">
                            <i class="fa-solid fa-rotate me-2"></i> Update Produk
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
