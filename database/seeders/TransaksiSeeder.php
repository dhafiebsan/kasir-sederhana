<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $kasir = User::where('role', 'kasir')->first();
        if (!$kasir) {
            return;
        }

        $tehBotol   = Produk::where('nama_produk', 'Teh Botol')->first();
        $kopiSachet = Produk::where('nama_produk', 'Kopi Sachet')->first();
        $airMineral = Produk::where('nama_produk', 'Air Mineral 600ml')->first();
        $beras5kg   = Produk::where('nama_produk', 'Beras 5kg')->first();
        $mieInstan  = Produk::where('nama_produk', 'Mie Instan Goreng')->first();
        $kertasHVS  = Produk::where('nama_produk', 'Kertas HVS A4')->first();
        $pulpenGel  = Produk::where('nama_produk', 'Pulpen Gel')->first();

        // Riwayat transaksi terdistribusi selama 7 hari terakhir agar grafik dan dashboard terisi
        $dataTransaksi = [
            // 6 Hari Lalu
            [
                'hari'   => 6,
                'items'  => [
                    ['produk' => $tehBotol, 'qty' => 4],
                    ['produk' => $mieInstan, 'qty' => 6],
                ],
                'bayar'  => 50000,
            ],
            // 5 Hari Lalu
            [
                'hari'   => 5,
                'items'  => [
                    ['produk' => $airMineral, 'qty' => 8],
                    ['produk' => $pulpenGel, 'qty' => 4],
                ],
                'bayar'  => 50000,
            ],
            // 4 Hari Lalu
            [
                'hari'   => 4,
                'items'  => [
                    ['produk' => $kertasHVS, 'qty' => 2],
                    ['produk' => $tehBotol, 'qty' => 5],
                ],
                'bayar'  => 100000,
            ],
            // 3 Hari Lalu (Total > 100rb diskon 10%)
            [
                'hari'   => 3,
                'items'  => [
                    ['produk' => $beras5kg, 'qty' => 2], // 120.000
                ],
                'bayar'  => 110000,
            ],
            // 2 Hari Lalu
            [
                'hari'   => 2,
                'items'  => [
                    ['produk' => $tehBotol, 'qty' => 6],
                    ['produk' => $kopiSachet, 'qty' => 4],
                ],
                'bayar'  => 40000,
            ],
            // Kemarin
            [
                'hari'   => 1,
                'items'  => [
                    ['produk' => $mieInstan, 'qty' => 10],
                    ['produk' => $airMineral, 'qty' => 4],
                ],
                'bayar'  => 50000,
            ],
            // Hari Ini Transaksi 1
            [
                'hari'   => 0,
                'items'  => [
                    ['produk' => $tehBotol, 'qty' => 3],
                    ['produk' => $pulpenGel, 'qty' => 2],
                ],
                'bayar'  => 25000,
            ],
            // Hari Ini Transaksi 2
            [
                'hari'   => 0,
                'items'  => [
                    ['produk' => $airMineral, 'qty' => 2],
                    ['produk' => $kopiSachet, 'qty' => 3],
                ],
                'bayar'  => 20000,
            ],
        ];

        foreach ($dataTransaksi as $data) {
            $waktu = Carbon::today()->subDays($data['hari'])->setHour(rand(9, 16))->setMinute(rand(10, 50));

            $totalKotor = 0;
            $itemsDetail = [];
            foreach ($data['items'] as $it) {
                if ($it['produk']) {
                    $subtotal = $it['produk']->harga * $it['qty'];
                    $totalKotor += $subtotal;
                    $itemsDetail[] = [
                        'produk_id' => $it['produk']->id,
                        'jumlah'    => $it['qty'],
                        'subtotal'  => $subtotal,
                    ];
                }
            }

            $diskon = 0;
            if ($totalKotor > 100000) {
                $diskon = $totalKotor * 0.1;
            }

            $totalAkhir = $totalKotor - $diskon;
            $bayar = max($data['bayar'], $totalAkhir);
            $kembalian = $bayar - $totalAkhir;

            $trx = Transaksi::create([
                'user_id'     => $kasir->id,
                'total_harga' => $totalAkhir,
                'diskon'      => $diskon,
                'bayar'       => $bayar,
                'kembalian'   => $kembalian,
                'created_at'  => $waktu,
                'updated_at'  => $waktu,
            ]);

            foreach ($itemsDetail as $d) {
                DetailTransaksi::create([
                    'transaksi_id' => $trx->id,
                    'produk_id'    => $d['produk_id'],
                    'jumlah'       => $d['jumlah'],
                    'subtotal'     => $d['subtotal'],
                    'created_at'   => $waktu,
                    'updated_at'   => $waktu,
                ]);
            }
        }
    }
}

