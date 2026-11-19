# 🛒 Aplikasi Kasir Sederhana (Point of Sale - POS System)

Aplikasi kasir berbasis web yang modern, responsif, dan siap produksi, dibangun menggunakan **Laravel 11**, **Bootstrap 5**, dan **MySQL**. Aplikasi ini dirancang untuk mempermudah operasional transaksi kasir, manajemen stok barang, pencegahan anomali data, visualisasi performa penjualan di dashboard, serta pelaporan keuangan toko.

---

## 🌟 Fitur Utama

### 1. 🔐 Autentikasi Multi-Peran (Multi-Role Auth)
* **Admin:** Memiliki akses penuh ke Dashboard Statistik, Manajemen Master Kategori, Master Produk, dan Laporan Penjualan Lengkap.
* **Kasir:** Fokus pada antarmuka kasir (Point of Sale), antrean keranjang belanja, proses pembayaran, dan cetak struk belanja.

### 2. 📊 Dashboard Statistik & Grafik Interaktif
* **4 Kartu Metrik Hari Ini:** Omzet Hari Ini, Total Transaksi Hari Ini, Total Barang Terdaftar, dan Jumlah Produk yang Perlu Restock.
* **Grafik Tren Penjualan 7 Hari Terakhir (*Chart.js*):** Visualisasi fluktuasi pendapatan dari Senin hingga Minggu.
* **Grafik Donat 5 Produk Terlaris:** Menampilkan produk *best-seller* untuk mempermudah pengambilan keputusan stok.
* **Pantauan Stok Kritis:** Peringatan langsung produk yang menipis ($\le 10$) atau habis ($= 0$).
* **Feed Transaksi Terkini:** Memantau 5 struk belanja terakhir yang baru saja diinput oleh kasir.

### 3. 🏷️ Manajemen Master Kategori & Anti-Duplikasi Cerdas
* **Pencegahan Nama Serupa (*Similarity Check*):** Sistem otomatis menolak penambahan nama kategori yang serupa hanya karena variasi angka (misal: `Makanan2` atau `Makanan 2` jika sudah ada `Makanan`).
* **Algoritma Kemiripan Teks:** Menolak nama kategori yang memiliki kemiripan $\ge 85\%$ via `similar_text` untuk mencegah kesalahan pengetikan (*typo*).
* **Proteksi Hapus Data:** Kategori yang produknya sudah memiliki riwayat transaksi **tidak dapat dihapus** guna menjaga integritas data akuntansi.

### 4. 📦 Manajemen Master Produk & Stok
* **Dukungan Kolom Satuan:** Setiap produk memiliki satuan spesifik (*pcs, botol, rim, karung, bungkus, porsi, box, dll*).
* **Indikator Stok Cerdas:**
  * **Merah (`Habis (0)`):** Jika stok sama dengan 0.
  * **Kuning (`Menipis`):** Jika stok tersisa $\le 10$.
  * **Normal:** Jika stok $> 10$.
* **Proteksi Hapus Produk:** Mencegah penghapusan produk yang sudah pernah ditransaksikan.

### 5. 💳 Transaksi Kasir *Real-Time* (Point of Sale)
* **Pengurangan Stok di Keranjang Secara *Real-Time*:** Sisa stok di dropdown otomatis berkurang sesuai item yang sedang ada di antrean keranjang belanja.
* **Pencegahan Pembelian Melebihi Stok:** Input jumlah dibatasi atribut `max` dan divalidasi ganda di backend.
* **Otomasi Diskon 10%:** Belanja di atas Rp 100.000 otomatis mendapatkan potongan diskon 10%.
* **Kalkulasi Kembalian Otomatis:** Memvalidasi kecukupan uang bayar dan menghitung kembalian secara presisi.

### 6. 🧾 Cetak Struk Belanja Kasir
* Format struk termal monospaced yang rapi.
* Dukungan cetak langsung via tombol **Cetak Struk** (`window.print()`) dengan CSS khusus print yang menyembunyikan navigasi browser.

### 7. 📈 Laporan Penjualan Multi-Periode & Format Dokumen Resmi
* **Filter Periode Lengkap:**
  * **Hari Ini:** Rekap omzet dan item hari berjalan.
  * **Bulan Ini:** Rekap omzet bulanan.
  * **Tahun Ini:** Rekap keuangan tahun berjalan.
  * **Semua:** Rekap seluruh riwayat transaksi toko.
  * **Rentang Tanggal Kustom:** Pilihan fleksibel tanggal mulai s/d tanggal selesai.
* **Dual-Mode Tampilan:** Tampilan visual interaktif di layar web, dan otomatis berubah menjadi **Format Dokumen Resmi Bergaris Lengkap dengan Kolom Tanda Tangan** saat dicetak ke kertas/PDF.

---

## 🛠️ Teknologi yang Digunakan

* **Backend:** PHP 8.2+ & Laravel 11
* **Database:** MySQL / MariaDB
* **Frontend:** Blade Templating, Bootstrap 5.3, FontAwesome 6
* **Visualisasi Data:** Chart.js
* **Font:** Inter (Google Fonts)

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal:

### 1. Clone Repositori
```bash
git clone https://github.com/USERNAME/kasir-sederhana.git
cd kasir-sederhana
```

### 2. Instal Dependensi Composer
```bash
composer install
```

### 3. Konfigurasi Lingkungan (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Pastikan pengaturan koneksi database di file `.env` sudah sesuai (misal menggunakan Laragon/XAMPP):
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_kasir
DB_USERNAME=root
DB_PASSWORD=
```
*(Pastikan database `db_kasir` sudah dibuat di phpMyAdmin / MySQL)*.

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Jalankan Migrasi & Seeder Data Dummy
Perintah ini akan membuat seluruh tabel dan mengisinya dengan data uji coba lengkap:
```bash
php artisan migrate:fresh --seed
```

### 6. Jalankan Server Lokal
```bash
php artisan serve
```
Aplikasi dapat diakses melalui browser di: **`http://127.0.0.1:8000`**

---

## 🔑 Akun Uji Coba Default

Aplikasi sudah dilengkapi dengan akun bawaan dari Seeder:

| Peran (Role) | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@gmail.com` | `admin123` | Akses penuh dashboard, kategori, produk, dan laporan |
| **Kasir** | `kasir@gmail.com` | `kasir123` | Akses antarmuka transaksi kasir dan struk |

---

## 🧪 Pengujian Sistem (Software Testing)

Proyek ini telah melalui pengujian menyeluruh (100% Pass) dengan teknik:
1. **Equivalence Partitioning (EP):** Menguji kelas data valid dan tidak valid pada proses login, CRUD, keranjang, transaksi, dan filter laporan.
2. **Boundary Value Analysis (BVA):** Menguji nilai batas harga ($\ge 0$), batas stok ($\ge 0$), panjang satuan ($\le 20$ karakter), ambang diskon ($> 100.000$), batas stok kritis ($\le 10$), dan batas tanggal inklusif laporan.
3. **White Box Testing (Branch Coverage):** Memastikan seluruh percabangan kondisi (*if-else*) pada Controller teruji dan tereksekusi dengan baik.


