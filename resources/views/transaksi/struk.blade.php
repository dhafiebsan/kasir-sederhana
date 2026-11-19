@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="d-flex justify-content-between mb-3 no-print">
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Kasir
            </a>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-print me-1"></i> Cetak Struk
            </button>
        </div>

        <div class="card border-0 shadow-sm p-4 receipt-box" style="font-family: 'Courier New', monospace;">
            <div class="text-center mb-3">
                <h5 class="fw-bold mb-0">TOKO KASIR PINTAR</h5>
                <small class="text-muted">ID Transaksi: #{{ $transaksi->id }}</small>
            </div>

            <div class="small mb-2">
                Kasir: {{ $transaksi->user->name }} <br>
                Waktu: {{ $transaksi->created_at->format('d/m/Y H:i') }}
            </div>

            <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>

            @foreach($transaksi->detail as $item)
            <div class="mb-2">
                <div class="d-flex justify-content-between">
                    <span>{{ $item->produk->nama_produk }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                <small class="text-muted">
                    {{ $item->jumlah }} {{ $item->produk->satuan ?? 'pcs' }} x Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                </small>
            </div>
            @endforeach

            <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>

            <div class="d-flex justify-content-between small">
                <span>Diskon:</span>
                <span>- Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between fw-bold my-1">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between small">
                <span>BAYAR:</span>
                <span>Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between fw-bold mt-1">
                <span>KEMBALIAN:</span>
                <span>Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
            </div>

            <div class="text-center mt-4">
                <small>*** TERIMA KASIH ***</small>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS Khusus untuk tampilan saat di-print */
    @media print {
        .no-print, .navbar, footer, .btn {
            display: none !important;
        }

        body {
            background-color: white !important;
            margin: 0;
            padding: 0;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .receipt-box {
            padding: 0 !important;
            margin: 0 !important;
        }
    }
</style>
@endsection
