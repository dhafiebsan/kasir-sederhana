<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = ['user_id', 'total_harga', 'diskon', 'bayar', 'kembalian'];

    // Relasi: Satu transaksi punya banyak detail barang yang dibeli
    public function detail()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    public function user()
    {
        // Transaksi ini dicatat oleh satu User (Kasir)
        return $this->belongsTo(User::class);
    }
}
