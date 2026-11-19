<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Hanya Admin yang bisa melihat data kategori.');
        }

        // Mengambil semua data dari tabel kategori menggunakan Model Kategori
        $kategori = Kategori::all();

        // Mengirim data kategori ke halaman view (tabel daftar kategori)
        return view('kategori.index', compact('kategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Menampilkan halaman form untuk menambah kategori baru
        return view('kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi: nama_kategori wajib diisi dan tidak boleh ada yang persis sama
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori'
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah digunakan.',
        ]);

        // Cek kemiripan nama kategori (misal: "Makanan" vs "Makanan2", "Makanan 2")
        $similarError = $this->checkSimilarKategori($request->nama_kategori);
        if ($similarError) {
            return back()->withErrors(['nama_kategori' => $similarError])->withInput();
        }

        // Menyimpan data kiriman form ke database
        Kategori::create([
            'nama_kategori' => trim($request->nama_kategori)
        ]);

        // Setelah simpan, balik ke halaman index dengan pesan sukses
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kategori $kategori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Mencari data kategori berdasarkan ID, jika tidak ada maka akan error 404
        $kategori = Kategori::findOrFail($id);

        // Menampilkan halaman form edit dengan membawa data kategori yang lama
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi: nama_kategori wajib diisi
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori,' . $id
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah digunakan.',
        ]);

        // Cek kemiripan nama kategori dengan kategori lain yang sudah ada
        $similarError = $this->checkSimilarKategori($request->nama_kategori, $id);
        if ($similarError) {
            return back()->withErrors(['nama_kategori' => $similarError])->withInput();
        }

        // Mencari data yang mau diupdate
        $kategori = Kategori::findOrFail($id);

        // Memperbarui data di database
        $kategori->update([
            'nama_kategori' => trim($request->nama_kategori)
        ]);

        // Balik ke tabel utama dengan pesan sukses
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Mencari data yang mau dihapus berdasarkan ID
        $kategori = Kategori::findOrFail($id);

        // 1. Cek apakah ada produk dalam kategori ini yang sudah pernah masuk ke transaksi penjualan
        $terhubungTransaksi = DetailTransaksi::whereHas('produk', function ($q) use ($id) {
            $q->where('kategori_id', $id);
        })->exists();

        if ($terhubungTransaksi) {
            return redirect()->route('kategori.index')
                ->with('error', 'Kategori "' . $kategori->nama_kategori . '" tidak dapat dihapus karena produk di dalamnya sudah memiliki riwayat transaksi penjualan!');
        }

        // 2. Cek apakah masih memiliki produk aktif yang terdaftar
        if ($kategori->produk()->exists()) {
            return redirect()->route('kategori.index')
                ->with('error', 'Kategori "' . $kategori->nama_kategori . '" tidak dapat dihapus karena masih memuat ' . $kategori->produk()->count() . ' produk aktif. Hapus atau pindahkan produk tersebut terlebih dahulu!');
        }

        // Menghapus data dari tabel kategori
        $kategori->delete();

        // Balik ke tabel utama dengan pesan sukses
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }

    /**
     * Memeriksa apakah ada nama kategori yang serupa atau variasi angka/simbol (misal: "Makanan" vs "Makanan2")
     */
    private function checkSimilarKategori(string $inputName, $excludeId = null): ?string
    {
        $inputClean = trim($inputName);
        $inputLower = strtolower($inputClean);

        // Hapus angka, spasi, strip, atau garis bawah di akhir kata (misal "Makanan 2" -> "makanan", "Makanan2" -> "makanan")
        $inputBase = preg_replace('/[\s_\-\d]+$/', '', $inputLower);

        $query = Kategori::query();
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        $existing = $query->get();

        foreach ($existing as $item) {
            $existClean = trim($item->nama_kategori);
            $existLower = strtolower($existClean);
            $existBase = preg_replace('/[\s_\-\d]+$/', '', $existLower);

            // 1. Cek jika kata dasarnya sama (misal "Makanan" vs "Makanan2" atau "Makanan 2")
            if (!empty($inputBase) && !empty($existBase) && $inputBase === $existBase) {
                return "Nama kategori '{$inputName}' serupa dengan kategori '{$item->nama_kategori}' yang sudah ada. Mohon gunakan nama yang berbeda.";
            }

            // 2. Cek kemiripan teks menggunakan similar_text (threshold >= 85%)
            similar_text($inputLower, $existLower, $percent);
            if ($percent >= 85) {
                return "Nama kategori '{$inputName}' terlalu mirip dengan kategori '{$item->nama_kategori}' (" . round($percent) . "% mirip).";
            }
        }

        return null;
    }
}
