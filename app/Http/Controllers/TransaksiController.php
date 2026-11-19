<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index()
    {
        // Jika bukan kasir, tendang ke halaman beranda/produk
        if (Auth::user()->role !== 'kasir') {
            return redirect('/')->with('error', 'Admin dilarang masuk ke menu transaksi.');
        }

        $keranjang = session()->get('keranjang', []);

        // Ambil semua produk dan hitung sisa stok riil setelah dikurangi barang yang ada di keranjang
        $produk = Produk::all()->map(function ($p) use ($keranjang) {
            $diKeranjang = isset($keranjang[$p->id]) ? (int) $keranjang[$p->id]['jumlah'] : 0;
            $p->sisa_stok = max(0, $p->stok - $diKeranjang);
            return $p;
        });

        $transaksi = Transaksi::with(['user'])
            ->latest()
            ->take(10)
            ->get();

        return view('transaksi.index', compact('produk', 'transaksi'));
    }

    /**
     * Menambahkan item ke keranjang belanja (Session)
     */
    public function tambahKeranjang(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'jumlah'    => 'required|integer|min:1',
        ], [
            'produk_id.required' => 'Produk wajib dipilih.',
            'produk_id.exists'   => 'Produk yang dipilih tidak valid atau tidak ditemukan.',
            'jumlah.required'    => 'Jumlah beli wajib diisi.',
            'jumlah.integer'     => 'Jumlah beli harus berupa bilangan bulat.',
            'jumlah.min'         => 'Jumlah beli minimal 1.',
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        $keranjang = session()->get('keranjang', []);

        // Hitung jumlah yang sudah ada di keranjang sebelumnya
        $jumlahLama = isset($keranjang[$produk->id]) ? (int) $keranjang[$produk->id]['jumlah'] : 0;
        $jumlahDiminta = (int) $request->jumlah;
        $jumlahBaru = $jumlahLama + $jumlahDiminta;

        // Hitung sisa stok aktual yang masih tersedia di luar keranjang
        $sisaStok = $produk->stok - $jumlahLama;

        // 1. Cek jika seluruh stok barang sudah habis terambil ke keranjang
        if ($sisaStok <= 0) {
            return back()->with('error', "Stok untuk produk '{$produk->nama_produk}' sudah habis! Seluruh {$produk->stok} {$produk->satuan} sudah berada di keranjang.");
        }

        // 2. Cek jika jumlah yang diminta melebihi sisa stok yang ada
        if ($jumlahBaru > $produk->stok) {
            return back()->with('error', "Jumlah beli melebihi stok! Sisa stok '{$produk->nama_produk}' yang tersedia hanya {$sisaStok} {$produk->satuan} lagi.");
        }

        // Simpan / update ke keranjang
        if (isset($keranjang[$produk->id])) {
            $keranjang[$produk->id]['jumlah'] = $jumlahBaru;
            $keranjang[$produk->id]['subtotal'] = $jumlahBaru * $produk->harga;
        } else {
            $keranjang[$produk->id] = [
                "nama"     => $produk->nama_produk,
                "jumlah"   => $jumlahDiminta,
                "harga"    => $produk->harga,
                "satuan"   => $produk->satuan ?? 'pcs',
                "subtotal" => $produk->harga * $jumlahDiminta
            ];
        }

        session()->put('keranjang', $keranjang);
        return redirect()->back()->with('success', 'Barang ditambahkan ke daftar belanja!');
    }

    /**
     * Menghapus item dari keranjang
     */
    public function hapusKeranjang($id)
    {
        $keranjang = session()->get('keranjang');
        if (isset($keranjang[$id])) {
            unset($keranjang[$id]);
            session()->put('keranjang', $keranjang);
        }
        return redirect()->back()->with('success', 'Barang dihapus dari daftar belanja!');
    }

    /**
     * PROSES PEMBAYARAN FINAL
     */
    public function store(Request $request)
    {
        $keranjang = session()->get('keranjang');

        // Validasi jika keranjang kosong
        if (!$keranjang) {
            return back()->with('error', 'Daftar belanja masih kosong!');
        }

        $request->validate([
            'bayar' => 'required|numeric',
        ], [
            'bayar.required' => 'Nominal uang bayar wajib diisi.',
            'bayar.numeric'  => 'Uang bayar harus berupa angka.',
        ]);

        // 1. Hitung Total dari Keranjang
        $totalHarga = 0;
        foreach ($keranjang as $item) {
            $totalHarga += $item['subtotal'];
        }

        // 2. Logika Diskon 10% jika > 100.000
        $diskon = 0;
        if ($totalHarga > 100000) {
            $diskon = $totalHarga * 0.1;
        }

        $totalAkhir = $totalHarga - $diskon;
        $kembalian = $request->bayar - $totalAkhir;

        // 3. Validasi Uang Bayar
        if ($kembalian < 0) {
            return redirect()->back()->with('error', 'Uang pembayaran tidak cukup!');
        }

        // 4. Simpan Transaksi Utama
        $transaksi = Transaksi::create([
            'user_id'     => Auth::id(),
            'total_harga' => $totalAkhir,
            'diskon'      => $diskon,
            'bayar'       => $request->bayar,
            'kembalian'   => $kembalian,
        ]);

        // 5. Simpan Detail Transaksi & Kurangi Stok
        foreach ($keranjang as $id => $item) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'produk_id'    => $id,
                'jumlah'       => $item['jumlah'],
                'subtotal'     => $item['subtotal'],
            ]);

            $produk = Produk::find($id);
            if ($produk) {
                $produk->decrement('stok', $item['jumlah']);
            }
        }

        // 6. Bersihkan Keranjang & Simpan ID untuk Struk
        session()->forget('keranjang');
        session()->put('id_transaksi_terakhir', $transaksi->id);

        return redirect()->route('transaksi.show')->with('success', 'Transaksi Berhasil!');
    }

    public function show()
    {
        $id = session('id_transaksi_terakhir');
        if (!$id) {
            return redirect()->route('transaksi.index')->with('error', 'Tidak ada transaksi yang dapat dicetak.');
        }

        $transaksi = Transaksi::with(['user', 'detail.produk'])->findOrFail($id);

        return view('transaksi.struk', compact('transaksi'));
    }

    /**
     * LAPORAN PENJUALAN: Harian, Bulanan, Tahunan, Semua, Custom Date Range
     */
    public function laporan(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Hanya Admin yang bisa melihat laporan.');
        }

        $periode = $request->get('periode', 'harian');
        $tglMulai = $request->get('tgl_mulai');
        $tglSelesai = $request->get('tgl_selesai');

        // Query DetailTransaksi dengan relasi produk & kategori
        $query = DetailTransaksi::with(['produk.kategori', 'transaksi.user'])
            ->whereHas('transaksi', function ($q) use ($periode, $tglMulai, $tglSelesai) {
                if ($periode === 'harian') {
                    $q->whereDate('created_at', Carbon::today());
                } elseif ($periode === 'bulanan') {
                    $q->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                } elseif ($periode === 'tahunan') {
                    $q->whereYear('created_at', Carbon::now()->year);
                } elseif ($periode === 'kustom' && $tglMulai && $tglSelesai) {
                    $q->whereDate('created_at', '>=', $tglMulai)
                      ->whereDate('created_at', '<=', $tglSelesai);
                }
                // Jika 'semua', tidak difilter tanggal
            });

        $produkTerjual = $query->latest('created_at')->get();

        // Hitung Statistik
        $totalPendapatan = $produkTerjual->sum('subtotal');
        $totalBarang     = $produkTerjual->sum('jumlah');

        // Hitung Total Transaksi
        $transaksiQuery = Transaksi::query();
        if ($periode === 'harian') {
            $transaksiQuery->whereDate('created_at', Carbon::today());
            $labelPeriode = 'Hari Ini (' . Carbon::today()->translatedFormat('d M Y') . ')';
        } elseif ($periode === 'bulanan') {
            $transaksiQuery->whereMonth('created_at', Carbon::now()->month)
                           ->whereYear('created_at', Carbon::now()->year);
            $labelPeriode = 'Bulan Ini (' . Carbon::now()->translatedFormat('F Y') . ')';
        } elseif ($periode === 'tahunan') {
            $transaksiQuery->whereYear('created_at', Carbon::now()->year);
            $labelPeriode = 'Tahun ' . Carbon::now()->format('Y');
        } elseif ($periode === 'kustom' && $tglMulai && $tglSelesai) {
            $transaksiQuery->whereDate('created_at', '>=', $tglMulai)
                           ->whereDate('created_at', '<=', $tglSelesai);
            $labelPeriode = Carbon::parse($tglMulai)->translatedFormat('d M Y') . ' s/d ' . Carbon::parse($tglSelesai)->translatedFormat('d M Y');
        } else {
            $periode = 'semua';
            $labelPeriode = 'Semua Periode Penjualan';
        }

        $totalTransaksi = $transaksiQuery->count();

        return view('laporan.index', compact(
            'produkTerjual',
            'totalPendapatan',
            'totalBarang',
            'totalTransaksi',
            'periode',
            'labelPeriode',
            'tglMulai',
            'tglSelesai'
        ));
    }
}
