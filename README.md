# Sistem Manajemen Restoran

Aplikasi web untuk manajemen restoran dengan fokus pada **pencatatan pengeluaran dan pemasukan** untuk laporan keuangan yang efisien dan akurat.

## Fitur Utama

### 📊 Laporan Keuangan (Core Feature)
- **Pencatatan Pemasukan**: Otomatis dari transaksi penjualan dengan tracking HPP (Harga Pokok Penjualan)
- **Pencatatan Pengeluaran**: Input manual pengeluaran operasional dengan kategori
- **Laporan Profit/Loss**: Kalkulasi laba kotor dan laba bersih
- **Export Data**: Export laporan ke CSV untuk analisis lebih lanjut
- **Dashboard Real-time**: Monitoring pemasukan/pengeluaran harian dan bulanan

### 🔐 Role Management
- **Admin**: Akses penuh ke semua fitur termasuk laporan keuangan
- **Kasir**: Akses ke POS dan transaksi penjualan

### 💰 Pencatatan Pemasukan (Transaksi)
- Point of Sale (POS) untuk transaksi kasir
- Auto-tracking HPP untuk analisis profit
- Invoice number otomatis
- Multiple payment methods (Cash, Card, QRIS, Transfer)
- Detail item per transaksi

### 💸 Pencatatan Pengeluaran
- Kategori pengeluaran (Gaji, Listrik, Bahan Baku, dll)
- Upload bukti pengeluaran (receipt)
- Tanggal pengeluaran yang fleksibel
- Tracking siapa yang input pengeluaran

### 📈 Analisis & Laporan
- **Laporan Penjualan**: Detail transaksi per periode
- **Laporan Profit**: Analisis profit margin per transaksi
- **Laporan Pengeluaran**: Breakdown pengeluaran per kategori
- **Grafik Harian**: Visualisasi pemasukan vs pengeluaran
- **Top Products**: Produk terlaris berdasarkan periode

### 🍽️ Manajemen Produk
- CRUD produk dengan kategori
- Input harga jual dan harga pokok
- Stock management
- Upload gambar produk

### 🏢 Fitur Tambahan
- Manajemen meja restoran
- Kategori produk
- User management (Admin only)

## Technology Stack

- **Backend**: Laravel 12
- **Database**: SQLite
- **Frontend**: Blade Templates
- **Authentication**: Laravel default Auth

## Instalasi

### Prerequisites
- PHP >= 8.2
- Composer
- SQLite3

### Setup

1. Install dependencies:
```bash
composer install
```

2. Setup environment (sudah ada .env):
```bash
php artisan key:generate
```

3. Jalankan migrations dan seed data:
```bash
php artisan migrate
php artisan db:seed
```

4. Buat folder untuk upload:
```bash
mkdir -p public/uploads/products
mkdir -p public/uploads/receipts
```

5. Jalankan development server:
```bash
php artisan serve
```

6. Akses aplikasi di `http://localhost:8000`

## Default Login Credentials

### Admin Account
- **Email**: admin@restaurant.com
- **Password**: admin123

### Kasir Account
- **Email**: kasir@restaurant.com
- **Password**: kasir123

## Struktur Database

### Core Tables untuk Keuangan:
- **transactions**: Menyimpan semua transaksi penjualan (Pemasukan)
- **transaction_items**: Detail item per transaksi dengan HPP
- **expenses**: Menyimpan semua pengeluaran operasional

### Supporting Tables:
- **users**: Admin dan Kasir
- **products**: Master produk dengan harga jual dan HPP
- **categories**: Kategori produk
- **tables**: Manajemen meja restoran

## Konsep Keuangan

### Perhitungan Profit
```
Laba Kotor = Total Penjualan - Total HPP
Laba Bersih = Laba Kotor - Total Pengeluaran
```

### Tracking Pemasukan
Setiap transaksi penjualan otomatis tercatat dengan:
- Harga jual (price)
- Harga pokok (cost)
- Profit per item

### Tracking Pengeluaran
Admin dapat input pengeluaran dengan kategori:
- Gaji Karyawan
- Listrik, Air, Gas
- Bahan Baku
- Peralatan
- Promosi & Marketing
- Sewa Tempat
- Maintenance
- Lain-lain

## Routes Structure

### Public Routes
- `GET /login` - Login page

### Authenticated Routes (Kasir & Admin)
- `GET /dashboard` - Dashboard
- `GET /pos` - Point of Sale
- `Resource /transactions` - CRUD Transaksi

### Admin Only Routes
- `Resource /products` - CRUD Produk
- `Resource /categories` - CRUD Kategori
- `Resource /expenses` - CRUD Pengeluaran
- `Resource /tables` - CRUD Meja
- `GET /reports` - Dashboard Laporan
- `GET /reports/sales` - Laporan Penjualan
- `GET /reports/profit` - Laporan Profit
- `GET /reports/expenses` - Laporan Pengeluaran
- `GET /reports/export` - Export ke CSV

## Cara Menggunakan

### Untuk Kasir:
1. Login dengan akun kasir
2. Lihat dashboard untuk ringkasan hari ini
3. Gunakan menu POS untuk membuat transaksi baru
4. Pilih produk, input quantity, dan proses pembayaran
5. Cetak/simpan invoice

### Untuk Admin:
1. Login dengan akun admin
2. Dashboard menampilkan:
   - Pemasukan & Pengeluaran hari ini dan bulan ini
   - Profit hari ini dan bulan ini
   - Transaksi terbaru
   - Produk stock rendah
   - Pengeluaran terbaru
3. Input pengeluaran di menu Expenses
4. Kelola produk, kategori, dan meja
5. Lihat laporan lengkap di menu Reports
6. Export laporan ke CSV untuk analisis

## License

Open source untuk pembelajaran dan pengembangan.
