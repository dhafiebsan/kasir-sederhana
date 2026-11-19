<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('transaksi.index');
        }

        // 1. STATISTIK KARTU HARI INI
        $omzetHariIni = Transaksi::whereDate('created_at', Carbon::today())->sum('total_harga');
        $transaksiHariIni = Transaksi::whereDate('created_at', Carbon::today())->count();
        $totalProduk = Produk::count();
        $stokKritisCount = Produk::where('stok', '<=', 10)->count();

        // 2. GRAFIK TREN PENJUALAN 7 HARI TERAKHIR
        $chartLabels = [];
        $chartDataOmzet = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = Carbon::today()->subDays($i);
            $chartLabels[] = $tgl->translatedFormat('d M'); // contoh: 29 Okt, 30 Okt
            $chartDataOmzet[] = (int) Transaksi::whereDate('created_at', $tgl)->sum('total_harga');
        }

        // 3. GRAFIK TOP 5 PRODUK TERLARIS
        $topProduk = DetailTransaksi::select('produk_id', DB::raw('SUM(jumlah) as total_terjual'))
            ->groupBy('produk_id')
            ->orderByDesc('total_terjual')
            ->with('produk')
            ->take(5)
            ->get();

        $pieLabels = [];
        $pieData = [];
        foreach ($topProduk as $tp) {
            $pieLabels[] = $tp->produk->nama_produk ?? 'Produk #' . $tp->produk_id;
            $pieData[] = (int) $tp->total_terjual;
        }

        // 4. DAFTAR 5 TRANSAKSI TERAKHIR
        $transaksiTerbaru = Transaksi::with(['user', 'detail.produk'])
            ->latest()
            ->take(5)
            ->get();

        // 5. DAFTAR PRODUK DENGAN STOK KRITIS (<= 10)
        $produkKritis = Produk::with('kategori')
            ->where('stok', '<=', 10)
            ->orderBy('stok', 'asc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'omzetHariIni',
            'transaksiHariIni',
            'totalProduk',
            'stokKritisCount',
            'chartLabels',
            'chartDataOmzet',
            'pieLabels',
            'pieData',
            'transaksiTerbaru',
            'produkKritis'
        ));
    }
}

