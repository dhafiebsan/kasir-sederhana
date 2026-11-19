<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $makanan = Kategori::where('nama_kategori', 'Makanan')->first();
        $minuman = Kategori::where('nama_kategori', 'Minuman')->first();
        $snack   = Kategori::where('nama_kategori', 'Snack')->first();
        $atk     = Kategori::where('nama_kategori', 'ATK')->first();

        // 1. Minuman
        Produk::create([
            'kategori_id' => $minuman->id,
            'nama_produk' => 'Teh Botol',
            'harga'       => 5000,
            'stok'        => 20,
            'satuan'      => 'botol',
        ]);

        Produk::create([
            'kategori_id' => $minuman->id,
            'nama_produk' => 'Kopi Sachet',
            'harga'       => 2000,
            'stok'        => 5, // Stok kritis <= 10 & uji BVA stok 5
            'satuan'      => 'pcs',
        ]);

        Produk::create([
            'kategori_id' => $minuman->id,
            'nama_produk' => 'Air Mineral 600ml',
            'harga'       => 3500,
            'stok'        => 50,
            'satuan'      => 'botol',
        ]);

        // 2. Makanan
        Produk::create([
            'kategori_id' => $makanan->id,
            'nama_produk' => 'Roti',
            'harga'       => 6000,
            'stok'        => 0, // Stok habis untuk uji opsi disabled
            'satuan'      => 'bungkus',
        ]);

        Produk::create([
            'kategori_id' => $makanan->id,
            'nama_produk' => 'Beras 5kg',
            'harga'       => 60000,
            'stok'        => 10, // Stok kritis batas 10
            'satuan'      => 'karung',
        ]);

        Produk::create([
            'kategori_id' => $makanan->id,
            'nama_produk' => 'Mie Instan Goreng',
            'harga'       => 3500,
            'stok'        => 80,
            'satuan'      => 'bungkus',
        ]);

        // 3. ATK
        Produk::create([
            'kategori_id' => $atk->id,
            'nama_produk' => 'Kertas HVS A4',
            'harga'       => 35000,
            'stok'        => 5,
            'satuan'      => 'rim',
        ]);

        Produk::create([
            'kategori_id' => $atk->id,
            'nama_produk' => 'Pulpen Gel',
            'harga'       => 3500,
            'stok'        => 25,
            'satuan'      => 'pcs',
        ]);

        // 4. Snack (Belum pernah ditransaksikan untuk uji coba hapus kategori/produk)
        Produk::create([
            'kategori_id' => $snack->id,
            'nama_produk' => 'Keripik Kentang',
            'harga'       => 12000,
            'stok'        => 30,
            'satuan'      => 'bungkus',
        ]);
    }
}

