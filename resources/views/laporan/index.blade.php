@extends('layouts.app')

@section('content')
<!-- ========================================== -->
<!-- 1. TAMPILAN WEB (VISUAL DASHBOARD)         -->
<!-- ========================================== -->
            <div class="d-print-none">
                
                <!-- HEADER & TOMBOL CETAK (TANPA TOMBOL KEMBALI) -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">
                            <i class="fa-solid fa-chart-line text-primary me-2"></i>Laporan Penjualan
                        </h3>
                        <p class="text-muted small mb-0 d-flex align-items-center">
                            Periode: <span class="badge bg-primary text-white px-3 py-2 ms-2 fw-semibold fs-6 shadow-sm"><i class="fa-regular fa-calendar-check me-1"></i> {{ $labelPeriode }}</span>
                        </p>
                    </div>
                    <div>
                        <button onclick="window.print()" class="btn btn-primary px-4 shadow-sm">
                            <i class="fa-solid fa-print me-1"></i> Cetak Laporan
                        </button>
                    </div>
                </div>

                <!-- FILTER PERIODE CEPAT & TANGGAL KUSTOM -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-3">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="btn-group" role="group">
                                <a href="{{ route('transaksi.laporan', ['periode' => 'harian']) }}" 
                                   class="btn btn-sm {{ $periode === 'harian' ? 'btn-primary fw-bold' : 'btn-outline-secondary' }}">
                                    <i class="fa-regular fa-calendar-day me-1"></i> Hari Ini
                                </a>
                                <a href="{{ route('transaksi.laporan', ['periode' => 'bulanan']) }}" 
                                   class="btn btn-sm {{ $periode === 'bulanan' ? 'btn-primary fw-bold' : 'btn-outline-secondary' }}">
                                    <i class="fa-regular fa-calendar me-1"></i> Bulan Ini
                                </a>
                                <a href="{{ route('transaksi.laporan', ['periode' => 'tahunan']) }}" 
                                   class="btn btn-sm {{ $periode === 'tahunan' ? 'btn-primary fw-bold' : 'btn-outline-secondary' }}">
                                    <i class="fa-solid fa-calendar-days me-1"></i> Tahun Ini
                                </a>
                                <a href="{{ route('transaksi.laporan', ['periode' => 'semua']) }}" 
                                   class="btn btn-sm {{ $periode === 'semua' ? 'btn-primary fw-bold' : 'btn-outline-secondary' }}">
                                    <i class="fa-solid fa-infinity me-1"></i> Semua
                                </a>
                            </div>

                            <!-- FILTER RENTANG TANGGAL KUSTOM -->
                            <form action="{{ route('transaksi.laporan') }}" method="GET" class="d-flex align-items-center gap-2">
                                <input type="hidden" name="periode" value="kustom">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-muted">Dari:</span>
                                    <input type="date" name="tgl_mulai" class="form-control form-control-sm" value="{{ $tglMulai ?? request('tgl_mulai') }}" required>
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-muted">Sampai:</span>
                                    <input type="date" name="tgl_selesai" class="form-control form-control-sm" value="{{ $tglSelesai ?? request('tgl_selesai') }}" required>
                                </div>
                                <button type="submit" class="btn btn-sm btn-dark px-3">
                                    <i class="fa-solid fa-filter me-1"></i> Filter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- KARTU STATISTIK METRIK (VISUAL DASHBOARD) -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm overflow-hidden h-100">
                            <div class="card-body p-4 border-start border-4 border-success">
                                <p class="text-muted small text-uppercase fw-bold mb-1">Total Pendapatan</p>
                                <h3 class="text-success fw-bold mb-0">
                                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm overflow-hidden h-100">
                            <div class="card-body p-4 border-start border-4 border-primary">
                                <p class="text-muted small text-uppercase fw-bold mb-1">Total Produk Terjual</p>
                                <h3 class="text-primary fw-bold mb-0">
                                    {{ number_format($totalBarang, 0, ',', '.') }} <span class="h6 text-muted fw-normal">Item</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm overflow-hidden h-100">
                            <div class="card-body p-4 border-start border-4 border-warning">
                                <p class="text-muted small text-uppercase fw-bold mb-1">Total Transaksi Selesai</p>
                                <h3 class="text-warning text-dark fw-bold mb-0">
                                    {{ number_format($totalTransaksi, 0, ',', '.') }} <span class="h6 text-muted fw-normal">Struk Selesai</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TABEL RINCIAN PRODUK TERJUAL (VISUAL DASHBOARD) -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Rincian Penjualan</h5>
                        <span class="badge bg-light text-secondary border px-3 py-2">
                            {{ $produkTerjual->count() }} Data Baris
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3" width="60px">No</th>
                                        <th class="py-3">Waktu</th>
                                        <th class="py-3">No. Struk</th>
                                        <th class="py-3">Nama Produk</th>
                                        <th class="py-3">Kategori</th>
                                        <th class="py-3 text-center">Qty / Satuan</th>
                                        <th class="py-3 text-end pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($produkTerjual as $item)
                                    <tr>
                                        <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                                        <td class="small text-muted">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="small fw-semibold text-secondary">#{{ $item->transaksi_id }}</td>
                                        <td class="fw-bold text-dark">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                                        <td>
                                            <span class="badge bg-light text-primary border border-primary border-opacity-25 px-2 py-1">
                                                {{ $item->produk->kategori->nama_kategori ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary bg-opacity-10 text-dark px-3 py-2">
                                                {{ $item->jumlah }} {{ $item->produk->satuan ?? 'pcs' }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-dark">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-file-invoice-dollar fa-3x mb-3 opacity-25"></i><br>
                                            Tidak ada transaksi penjualan pada periode ini.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                
                                @if($produkTerjual->count() > 0)
                                <tfoot class="bg-light fw-bold">
                                    <tr>
                                        <td colspan="5" class="text-center py-3 ps-4">TOTAL AKUMULASI</td>
                                        <td class="text-center py-3 text-primary">{{ number_format($totalBarang, 0, ',', '.') }}</td>
                                        <td class="text-end py-3 pe-4 text-success">
                                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

            </div>


            <!-- ========================================== -->
            <!-- 2. TAMPILAN PREVIEW CETAK (FORMAL DOKUMEN) -->
            <!-- ========================================== -->
            <div class="d-none d-print-block print-document">
                <!-- KOP SURAT / HEADER RESMI -->
                <div class="text-center pb-3 border-bottom mb-4">
                    <h3 class="fw-bold text-uppercase mb-1" style="letter-spacing: 1.5px;">LAPORAN PENJUALAN TOKO</h3>
                    <p class="text-muted small mb-0">Aplikasi Kasir Sederhana &bull; Point of Sale System</p>
                </div>

                <!-- METADATA LAPORAN -->
                <div class="row mb-4 small">
                    <div class="col-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted ps-0" width="130px">Periode Laporan</td>
                                <td class="fw-bold">: {{ $labelPeriode }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Tanggal Cetak</td>
                                <td>: {{ now()->translatedFormat('d F Y, H:i') }} WIB</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Dicetak Oleh</td>
                                <td>: {{ Auth::user()->name }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted ps-0" width="160px">Total Transaksi Selesai</td>
                                <td class="fw-semibold">: {{ number_format($totalTransaksi, 0, ',', '.') }} Transaksi</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Total Item Terjual</td>
                                <td class="fw-semibold">: {{ number_format($totalBarang, 0, ',', '.') }} Item</td>
                            </tr>
                            <tr>
                                <td class="text-muted ps-0">Total Pendapatan</td>
                                <td class="fw-bold">: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- TABEL DOKUMEN FORMAL BERGARIS -->
                <table class="table table-bordered align-middle formal-print-table">
                    <thead>
                        <tr class="text-center small fw-bold">
                            <th width="45px">No</th>
                            <th width="130px">Waktu</th>
                            <th width="80px">No. Struk</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th width="100px">Jumlah</th>
                            <th width="120px" class="text-end">Harga Satuan</th>
                            <th width="130px" class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produkTerjual as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center small">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center small font-monospace">#{{ $item->transaksi_id }}</td>
                            <td>{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                            <td>{{ $item->produk->kategori->nama_kategori ?? '-' }}</td>
                            <td class="text-center">{{ $item->jumlah }} {{ $item->produk->satuan ?? 'pcs' }}</td>
                            <td class="text-end small">Rp {{ number_format($item->produk->harga ?? 0, 0, ',', '.') }}</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-3 text-muted fst-italic">
                                Tidak ada catatan transaksi penjualan pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    
                    @if($produkTerjual->count() > 0)
                    <tfoot class="fw-bold">
                        <tr>
                            <td colspan="5" class="text-end pe-3">TOTAL KESELURUHAN :</td>
                            <td class="text-center">{{ number_format($totalBarang, 0, ',', '.') }}</td>
                            <td class="text-center">-</td>
                            <td class="text-end">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>

                <!-- PENGESAHAN DOKUMEN CETAK -->
                <div class="row mt-5 pt-4">
                    <div class="col-6 text-center">
                        <small class="text-muted d-block mb-5">Mengetahui, Supervisor / Manajer</small>
                        <span class="d-inline-block border-bottom px-5 pb-1 fw-bold">( ........................................ )</span>
                    </div>
                    <div class="col-6 text-center">
                        <small class="text-muted d-block mb-5">Dibuat Oleh, Administrator</small>
                        <span class="d-inline-block border-bottom px-5 pb-1 fw-bold">( {{ Auth::user()->name }} )</span>
                    </div>
                </div>
            </div>

<style>
    /* Styling Tabel di Web */
    .table thead th {
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-weight: 700;
        color: #6c757d;
    }

    /* Styling Khusus Media Cetak / Print Preview */
    @media print {
        @page {
            margin: 15mm 12mm 15mm 12mm;
        }
        .navbar, footer {
            display: none !important;
        }
        body {
            background-color: #ffffff !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .main-content {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
        .print-document {
            margin-top: 2.5rem !important;
            padding-top: 1rem !important;
        }
        .container {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .formal-print-table,
        .formal-print-table th,
        .formal-print-table td {
            border: 1px solid #000 !important;
            font-size: 0.85rem !important;
            padding: 0.45rem 0.6rem !important;
        }
        .formal-print-table thead th {
            background-color: #f1f5f9 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@endsection
