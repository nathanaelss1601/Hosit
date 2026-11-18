# Quick Start Guide - Restaurant Management System

## 🚀 Instalasi Cepat

### 1. Install Dependencies
```bash
composer install
```

### 2. Setup Database
```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### 3. Jalankan Server
```bash
php artisan serve
```

### 4. Akses Aplikasi
Buka browser: `http://localhost:8000`

## 🔑 Login

### Admin (Akses Penuh)
- Email: `admin@restaurant.com`
- Password: `admin123`

### Kasir (Akses POS & Transaksi)
- Email: `kasir@restaurant.com`  
- Password: `kasir123`

## 📊 Fitur Utama

### Untuk KASIR:
1. **Dashboard** - Lihat pemasukan hari ini dan bulan ini
2. **POS (Point of Sale)** - Buat transaksi penjualan baru
3. **Riwayat Transaksi** - Lihat transaksi yang sudah dibuat

### Untuk ADMIN:
1. **Dashboard** - Monitor lengkap pemasukan, pengeluaran, dan profit
2. **Transaksi** - Lihat semua transaksi
3. **Pengeluaran** - Catat pengeluaran operasional
4. **Laporan Keuangan** - Analisis lengkap keuangan dengan:
   - Laba Kotor (Pemasukan - HPP)
   - Laba Bersih (Laba Kotor - Pengeluaran)
   - Grafik harian
   - Top products
   - Export CSV
5. **Produk** - Kelola menu dengan harga jual & HPP
6. **Kategori** - Kelola kategori produk
7. **Meja** - Kelola meja restoran

## 💰 Cara Pencatatan Keuangan

### Pemasukan (Otomatis dari Transaksi)
```
Kasir buat transaksi → System otomatis:
  ✓ Catat harga jual
  ✓ Catat harga pokok (HPP)
  ✓ Hitung profit per item
  ✓ Update stock
```

### Pengeluaran (Manual oleh Admin)
```
Admin input pengeluaran → Pilih kategori:
  • Gaji Karyawan
  • Listrik, Air, Gas
  • Bahan Baku
  • Sewa Tempat
  • dll
```

### Laporan
```
Admin akses Reports → Lihat:
  • Total Pemasukan
  • Total HPP
  • Laba Kotor = Pemasukan - HPP
  • Total Pengeluaran
  • Laba Bersih = Laba Kotor - Pengeluaran
  • Export ke CSV
```

## 🎯 Flow Penggunaan Harian

### Pagi (Buka Restoran):
1. Kasir login
2. Cek dashboard untuk starting point

### Siang (Operasional):
1. Customer datang
2. Kasir buka POS
3. Pilih produk yang dipesan
4. Input quantity
5. Pilih payment method
6. Submit transaksi
7. Invoice otomatis generated

### Sore (Pengeluaran):
1. Admin login
2. Catat pengeluaran hari ini
3. Upload bukti (receipt)

### Malam (Laporan):
1. Admin buka Reports
2. Review pemasukan & pengeluaran hari ini
3. Cek profit hari ini
4. Analisis produk terlaris
5. Export data jika perlu

## 📁 Database Schema Penting

### transactions (Pemasukan)
- Menyimpan semua penjualan
- Auto-track invoice number
- Linked ke items untuk detail

### transaction_items
- Detail produk per transaksi
- **Penting**: Menyimpan `price` dan `cost` untuk analisis profit

### expenses (Pengeluaran)
- Semua pengeluaran operasional
- Kategorisasi untuk analisis

## 🔢 Contoh Perhitungan

### Transaksi:
```
Item 1: Nasi Goreng
  - Harga Jual: Rp 25,000
  - HPP: Rp 10,000
  - Quantity: 2
  - Profit: (25,000 - 10,000) × 2 = Rp 30,000

Item 2: Es Teh
  - Harga Jual: Rp 5,000
  - HPP: Rp 2,000
  - Quantity: 1
  - Profit: (5,000 - 2,000) × 1 = Rp 3,000

Total Transaksi: Rp 55,000
Total Profit: Rp 33,000
```

### Laporan Harian:
```
Pemasukan: Rp 500,000 (dari 20 transaksi)
HPP: Rp 200,000
Laba Kotor: Rp 300,000

Pengeluaran:
  - Listrik: Rp 50,000
  - Bahan Baku: Rp 100,000
  Total: Rp 150,000

Laba Bersih: Rp 300,000 - Rp 150,000 = Rp 150,000
```

## 🛠️ Commands Berguna

### Reset Database
```bash
php artisan migrate:fresh --seed
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Cek Routes
```bash
php artisan route:list
```

### Database Info
```bash
php artisan db:show
```

## 📝 Catatan Penting

### Data Awal (Setelah Seeder):
- ✅ 2 Users (1 Admin, 1 Kasir)
- ✅ 4 Kategori (Makanan, Minuman, Snack, Dessert)
- ✅ 8 Produk dengan harga & HPP
- ✅ 10 Meja

### Keamanan:
- Password di-hash dengan bcrypt
- Middleware untuk protect admin routes
- Session-based authentication
- CSRF protection

### Best Practices:
1. Selalu input **Harga Pokok (HPP)** saat tambah produk
2. Catat pengeluaran setiap hari
3. Review laporan minimal seminggu sekali
4. Export data bulanan untuk backup
5. Pastikan stock akurat

## 🎨 Next: Membuat Views

Backend sudah lengkap. Untuk UI, buat views di:
```
resources/views/
  ├── auth/login.blade.php
  ├── dashboard/index.blade.php
  ├── transactions/
  │   ├── index.blade.php
  │   ├── show.blade.php
  │   └── pos.blade.php
  ├── expenses/
  │   ├── index.blade.php
  │   └── create.blade.php
  └── reports/
      ├── index.blade.php
      ├── sales.blade.php
      └── profit.blade.php
```

## 🆘 Troubleshooting

### Error: "SQLSTATE[HY000]: General error: 1 no such table"
```bash
php artisan migrate
```

### Error: "Class 'App\Models\...' not found"
```bash
composer dump-autoload
```

### Error: Storage link not working
```bash
php artisan storage:link
```

### Error: Permission denied
```bash
chmod -R 775 storage bootstrap/cache
```

## 📞 Support

Untuk pertanyaan atau issue, cek:
- `README.md` - Overview sistem
- `DOCUMENTATION.md` - Detail teknis
- `TESTING.md` - Testing guide

---

**Sistem siap digunakan!** 🎉

Backend sudah 100% selesai dengan fitur:
✅ Pencatatan Pemasukan (otomatis dari transaksi)
✅ Pencatatan Pengeluaran (manual dengan kategori)  
✅ Laporan Keuangan (akurat dengan profit tracking)
✅ Role Management (Admin & Kasir)
✅ Export CSV

Tinggal tambahkan Views untuk UI yang lengkap!
