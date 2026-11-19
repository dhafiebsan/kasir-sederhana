@extends('layouts.app')

@section('content')
<div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 text-dark fw-bold">
                        <i class="fa-solid fa-cart-plus me-2 text-primary"></i>Tambah Barang
                    </h6>
                </div>
                <div class="card-body p-4 pt-1">
                    <form action="{{ route('transaksi.tambah-keranjang') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-secondary">Pilih Produk</label>
                            <select name="produk_id" id="produkSelect" class="form-select bg-light border-0 py-2" required>
                                <option value="" selected disabled>-- Cari Barang --</option>
                                @foreach($produk as $p)
                                    @if($p->sisa_stok > 0)
                                        <option value="{{ $p->id }}" data-stok="{{ $p->sisa_stok }}" data-satuan="{{ $p->satuan ?? 'pcs' }}">
                                            {{ $p->nama_produk }} — Rp {{ number_format($p->harga, 0, ',', '.') }} (Sisa: {{ $p->sisa_stok }} {{ $p->satuan ?? 'pcs' }})
                                        </option>
                                    @else
                                        <option value="{{ $p->id }}" disabled class="text-muted bg-light">
                                            {{ $p->nama_produk }} — (Stok Habis / di Keranjang)
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-secondary">Jumlah Beli</label>
                            <input type="number" name="jumlah" id="jumlahInput" class="form-control bg-light border-0 py-2" value="1" min="1" required>
                            <small id="stokHint" class="d-block mt-1 text-muted" style="font-size: 0.8rem;"></small>
                        </div>
                        <button type="submit" id="btnTambahKeranjang" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                            <i class="fa-solid fa-cart-arrow-down me-2"></i> Masukkan ke Keranjang
                        </button>
                    </form>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm">
                    <i class="fa-solid fa-circle-xmark me-2"></i> {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm text-dark">
                    <i class="fa-solid fa-circle-check me-2 text-success"></i> {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fa-solid fa-receipt me-2 text-primary"></i>Daftar Belanjaan
                    </h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fw-semibold">
                        {{ now()->translatedFormat('d F Y') }}
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light small fw-bold text-uppercase text-secondary">
                                <tr>
                                    <th class="ps-4 py-3">Produk</th>
                                    <th class="py-3">Harga</th>
                                    <th class="py-3 text-center">Qty / Satuan</th>
                                    <th class="py-3 text-end">Subtotal</th>
                                    <th class="py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalSemua = 0; @endphp
                                @if(session('keranjang'))
                                    @foreach(session('keranjang') as $id => $item)
                                        @php $totalSemua += $item['subtotal']; @endphp
                                        <tr>
                                            <td class="ps-4">
                                                <span class="fw-bold text-dark">{{ $item['nama'] }}</span>
                                            </td>
                                            <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark px-3 py-2">
                                                    {{ $item['jumlah'] }} {{ $item['satuan'] ?? 'pcs' }}
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold text-primary">
                                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('transaksi.hapus-keranjang', $id) }}" method="POST">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0" title="Hapus item">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="fa-solid fa-basket-shopping fa-3x text-muted opacity-25 mb-3"></i><br>
                                            <span class="text-muted small">Belum ada barang di daftar belanja.</span>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if(session('keranjang'))
                    <div class="p-4 bg-light border-top">
                        <form action="{{ route('transaksi.store') }}" method="POST">
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-md-5 mb-3 mb-md-0">
                                    <small class="text-secondary fw-bold text-uppercase d-block mb-1">Total Belanja</small>
                                    <h2 class="mb-0 fw-bold text-primary">Rp {{ number_format($totalSemua, 0, ',', '.') }}</h2>
                                    
                                    @if($totalSemua > 100000)
                                        <small class="text-success fw-bold">
                                            <i class="fa-solid fa-tag"></i> Diskon 10% Otomatis Aktif
                                        </small>
                                    @endif
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <label class="small fw-bold text-uppercase text-secondary mb-1">Uang Bayar (Nominal)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-primary text-primary fw-bold">Rp</span>
                                        <input type="number" name="bayar" class="form-control border-primary fw-bold" placeholder="0" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100 fw-bold py-3 shadow-sm">
                                        <i class="fa-solid fa-cash-register me-2"></i> BAYAR SEKARANG
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

<style>
    .table td {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const produkSelect = document.getElementById('produkSelect');
        const jumlahInput = document.getElementById('jumlahInput');
        const stokHint = document.getElementById('stokHint');
        const btnTambah = document.getElementById('btnTambahKeranjang');

        function updateStokHint() {
            if (!produkSelect || produkSelect.selectedIndex < 0) return;
            const selected = produkSelect.options[produkSelect.selectedIndex];
            if (!selected || !selected.value) {
                if (stokHint) stokHint.innerHTML = '';
                return;
            }

            const sisaStok = parseInt(selected.getAttribute('data-stok') || 0);
            const satuan = selected.getAttribute('data-satuan') || 'pcs';

            if (jumlahInput) {
                jumlahInput.max = sisaStok;
                if (sisaStok > 0) {
                    if (parseInt(jumlahInput.value) > sisaStok || parseInt(jumlahInput.value) < 1) {
                        jumlahInput.value = 1;
                    }
                    if (stokHint) {
                        stokHint.innerHTML = '<span class="text-success"><i class="fa-solid fa-circle-check me-1"></i>Sisa tersedia: <strong>' + sisaStok + ' ' + satuan + '</strong></span>';
                    }
                    if (btnTambah) btnTambah.disabled = false;
                } else {
                    jumlahInput.value = 0;
                    if (stokHint) {
                        stokHint.innerHTML = '<span class="text-danger"><i class="fa-solid fa-circle-xmark me-1"></i>Stok sudah habis di keranjang!</span>';
                    }
                    if (btnTambah) btnTambah.disabled = true;
                }
            }
        }

        if (produkSelect) {
            produkSelect.addEventListener('change', updateStokHint);
        }

        if (jumlahInput) {
            jumlahInput.addEventListener('input', function () {
                const max = parseInt(this.max || 0);
                if (max > 0 && parseInt(this.value) > max) {
                    this.value = max;
                }
            });
        }
    });
</script>
@endsection
