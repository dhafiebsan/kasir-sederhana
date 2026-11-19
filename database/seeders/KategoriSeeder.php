<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        Kategori::create(['nama_kategori' => 'Makanan']);
        Kategori::create(['nama_kategori' => 'Minuman']);
        Kategori::create(['nama_kategori' => 'Snack']);
        Kategori::create(['nama_kategori' => 'ATK']);
        Kategori::create(['nama_kategori' => 'Elektronik']); // Kategori tanpa produk untuk uji hapus
    }
}

