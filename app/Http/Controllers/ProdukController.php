<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Hanya Admin yang bisa melihat data produk.');
        }

        // Mengambil data produk beserta data kategori terkait (Eager Loading)
        // Fungsi with('kategori') digunakan agar tidak terjadi query berulang (N+1 Problem)
        $produk = Produk::with('kategori')->get();
        
        // Menampilkan halaman daftar produk dengan membawa data produk
        return view('produk.index', compact('produk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mengambil semua data kategori untuk ditampilkan di dropdown pilihan kategori pada form
        $kategori = Kategori::all(); 
        
        // Menampilkan form tambah produk
        return view('produk.create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input: memastikan kategori dipilih, nama ada, serta harga & stok berupa angka positif
        $validated = $request->validate([
            'kategori_id' => 'required',
            'nama_produk' => 'required',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|numeric|min:0',
            'satuan'      => 'nullable|string|max:20',
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga.required'       => 'Harga wajib diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'harga.min'            => 'Harga minimal bernilai 0.',
            'stok.required'        => 'Stok wajib diisi.',
            'stok.numeric'         => 'Stok harus berupa angka.',
            'stok.min'             => 'Stok minimal bernilai 0.',
            'satuan.max'           => 'Satuan maksimal 20 karakter.',
        ]);

        if (empty($validated['satuan'])) {
            $validated['satuan'] = 'pcs';
        }

        // Menyimpan data produk baru ke database
        Produk::create($validated);
        
        // Kembali ke halaman daftar produk dengan pesan sukses
        return redirect()->route('produk.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Mencari data produk berdasarkan ID. Jika tidak ditemukan, akan muncul error 404.
        $produk = Produk::findOrFail($id);
        
        // Mengambil semua kategori agar user bisa mengubah kategori produk saat edit
        $kategori = Kategori::all(); 
        
        // Menampilkan form edit produk dengan data yang sudah ada
        return view('produk.edit', compact('produk', 'kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi data yang akan diupdate
        $validated = $request->validate([
            'kategori_id' => 'required',
            'nama_produk' => 'required',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|numeric|min:0',
            'satuan'      => 'nullable|string|max:20',
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga.required'       => 'Harga wajib diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'harga.min'            => 'Harga minimal bernilai 0.',
            'stok.required'        => 'Stok wajib diisi.',
            'stok.numeric'         => 'Stok harus berupa angka.',
            'stok.min'             => 'Stok minimal bernilai 0.',
            'satuan.max'           => 'Satuan maksimal 20 karakter.',
        ]);

        if (empty($validated['satuan'])) {
            $validated['satuan'] = 'pcs';
        }

        // Mencari produk yang akan diupdate
        $produk = Produk::findOrFail($id);
        
        // Melakukan update data berdasarkan input dari form
        $produk->update($validated);
        
        // Kembali ke halaman index dengan pesan sukses
        return redirect()->route('produk.index')->with('success', 'Data barang diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Cek apakah produk sudah pernah masuk ke transaksi
        $terhubungTransaksi = DetailTransaksi::where('produk_id', $id)->exists();
        if ($terhubungTransaksi) {
            return redirect()->route('produk.index')
                ->with('error', 'Produk "' . $produk->nama_produk . '" tidak dapat dihapus karena sudah memiliki riwayat transaksi!');
        }

        $produk->delete();
        
        // Kembali ke halaman daftar produk setelah penghapusan
        return redirect()->route('produk.index')->with('success', 'Barang berhasil dihapus!');
    }
}