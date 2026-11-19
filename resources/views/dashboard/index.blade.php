@extends('layouts.app')

@section('content')
<!-- HEADER DASHBOARD -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-gauge-high text-primary me-2"></i>Dashboard Utama
            </h3>
            <p class="text-muted small mb-0">
                Selamat Datang kembali, <strong>{{ Auth::user()->name }}</strong>! Pantau performa toko Anda hari ini.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-white text-secondary border px-3 py-2 shadow-sm">
                <i class="fa-regular fa-calendar me-1 text-primary"></i> {{ now()->translatedFormat('l, d F Y') }}
            </span>
            <a href="{{ route('transaksi.laporan') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="fa-solid fa-chart-line me-1"></i> Buka Laporan Lengkap
            </a>
        </div>
    </div>

    <!-- 4 KARTU STATISTIK UTAMA -->
    <div class="row g-3 mb-4">
        <!-- PENDAPATAN HARI INI -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-3 p-md-4 border-start border-4 border-success">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small text-uppercase fw-bold">Omzet Hari Ini</span>
                            <h4 class="fw-bold text-success mb-0 mt-1">
                                Rp {{ number_format($omzetHariIni, 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle">
                            <i class="fa-solid fa-wallet fa-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TRANSAKSI HARI INI -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-3 p-md-4 border-start border-4 border-primary">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small text-uppercase fw-bold">Transaksi Hari Ini</span>
                            <h4 class="fw-bold text-primary mb-0 mt-1">
                                {{ number_format($transaksiHariIni, 0, ',', '.') }} <span class="h6 text-muted fw-normal">Struk</span>
                            </h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                            <i class="fa-solid fa-receipt fa-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOTAL PRODUK AKTIF -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-3 p-md-4 border-start border-4 border-info">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small text-uppercase fw-bold">Total Barang</span>
                            <h4 class="fw-bold text-dark mb-0 mt-1">
                                {{ number_format($totalProduk, 0, ',', '.') }} <span class="h6 text-muted fw-normal">Item</span>
                            </h4>
                        </div>
                        <div class="bg-info bg-opacity-10 text-info p-3 rounded-circle">
                            <i class="fa-solid fa-boxes-stacked fa-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PERINGATAN STOK KRITIS -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-3 p-md-4 border-start border-4 border-warning">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small text-uppercase fw-bold">Perlu Restock</span>
                            <h4 class="fw-bold text-warning text-dark mb-0 mt-1">
                                {{ number_format($stokKritisCount, 0, ',', '.') }} <span class="h6 text-muted fw-normal">Produk</span>
                            </h4>
                        </div>
                        <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle">
                            <i class="fa-solid fa-triangle-exclamation fa-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW GRAFIK PENJUALAN (CHART.JS) -->
    <div class="row g-4 mb-4">
        <!-- GRAFIK TREN 7 HARI TERAKHIR -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fa-solid fa-chart-area text-primary me-2"></i>Tren Penjualan 7 Hari Terakhir
                    </h6>
                    <span class="badge bg-light text-muted small">Pembaruan Langsung</span>
                </div>
                <div class="card-body pt-0">
                    <div style="height: 300px; position: relative;">
                        <canvas id="salesTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRAFIK 5 PRODUK TERLARIS -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fa-solid fa-trophy text-warning me-2"></i>5 Produk Terlaris
                    </h6>
                    <span class="badge bg-light text-muted small">Paling Laris</span>
                </div>
                <div class="card-body pt-0 d-flex flex-column align-items-center justify-content-center">
                    @if(count($pieData) > 0)
                        <div style="width: 100%; max-height: 250px; position: relative;">
                            <canvas id="topProductsChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fa-solid fa-basket-shopping fa-3x opacity-25 mb-3"></i>
                            <p class="small mb-0">Belum ada riwayat penjualan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ROW TABEL AKTIVITAS TERKINI & STOK KRITIS -->
    <div class="row g-4">
        <!-- TRANSAKSI TERAKHIR -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Transaksi Terbaru Kasir
                    </h6>
                    <span class="small text-muted">5 Transaksi Terakhir</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small text-uppercase text-secondary">
                                <tr>
                                    <th class="ps-3 py-2">ID Struk</th>
                                    <th class="py-2">Kasir</th>
                                    <th class="py-2">Waktu</th>
                                    <th class="py-2 text-end pe-3">Total Belanja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksiTerbaru as $trx)
                                <tr>
                                    <td class="ps-3 font-monospace small fw-bold text-primary">#{{ $trx->id }}</td>
                                    <td class="small">{{ $trx->user->name ?? 'Kasir' }}</td>
                                    <td class="small text-muted">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-end pe-3 fw-bold text-dark">
                                        Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">
                                        Belum ada riwayat transaksi.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- STOK KRITIS PERLU RESTOCK -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fa-solid fa-bell text-danger me-2"></i>Perhatian Stok Menipis
                    </h6>
                    <a href="{{ route('produk.index') }}" class="btn btn-link btn-sm p-0 text-decoration-none">
                        Kelola Stok <i class="fa-solid fa-arrow-right small"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small text-uppercase text-secondary">
                                <tr>
                                    <th class="ps-3 py-2">Produk</th>
                                    <th class="py-2 text-center">Sisa Stok</th>
                                    <th class="py-2 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produkKritis as $pk)
                                <tr>
                                    <td class="ps-3">
                                        <span class="fw-semibold small text-dark d-block">{{ $pk->nama_produk }}</span>
                                        <small class="text-muted">{{ $pk->kategori->nama_kategori ?? '-' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold {{ $pk->stok == 0 ? 'text-danger' : 'text-warning' }}">
                                            {{ $pk->stok }} {{ $pk->satuan ?? 'pcs' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($pk->stok == 0)
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 small">
                                                Habis
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-25 text-dark border border-warning px-2 py-1 small">
                                                Menipis
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-success small">
                                        <i class="fa-solid fa-check-circle me-1"></i> Semua stok produk dalam kondisi aman!
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- SCRIPT CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // --- 1. INISIALISASI GRAFIK TREN PENJUALAN ---
        const ctxTrend = document.getElementById('salesTrendChart').getContext('2d');
        const trendGradient = ctxTrend.createLinearGradient(0, 0, 0, 300);
        trendGradient.addColorStop(0, 'rgba(79, 70, 229, 0.35)');
        trendGradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($chartDataOmzet) !!},
                    borderColor: '#4f46e5',
                    backgroundColor: trendGradient,
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Omzet: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000) + ' Jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000) + ' Rb';
                                return 'Rp ' + value;
                            },
                            font: { size: 11 }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });

        // --- 2. INISIALISASI GRAFIK 5 PRODUK TERLARIS ---
        @if(count($pieData) > 0)
        const ctxPie = document.getElementById('topProductsChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($pieLabels) !!},
                datasets: [{
                    data: {!! json_encode($pieData) !!},
                    backgroundColor: [
                        '#4f46e5',
                        '#06b6d4',
                        '#10b981',
                        '#f59e0b',
                        '#ec4899'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 12,
                            font: { size: 11 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.parsed + ' item terjual';
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
        @endif
    });
</script>
@endsection

