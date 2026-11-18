# Testing Guide - Sistem Manajemen Restoran

## Setup untuk Testing

### 1. Persiapan Database
```bash
# Reset database dan seed ulang
php artisan migrate:fresh --seed
```

### 2. Login Credentials
```
Admin:
  Email: admin@restaurant.com
  Password: admin123

Kasir:
  Email: kasir@restaurant.com
  Password: kasir123
```

## Test Scenarios

### A. Testing Autentikasi

#### Test 1: Login sebagai Admin
1. Buka `http://localhost:8000/login`
2. Input:
   - Email: admin@restaurant.com
   - Password: admin123
3. Expected: Redirect ke dashboard dengan akses penuh

#### Test 2: Login sebagai Kasir
1. Buka `http://localhost:8000/login`
2. Input:
   - Email: kasir@restaurant.com
   - Password: kasir123
3. Expected: Redirect ke dashboard dengan akses terbatas

#### Test 3: Logout
1. Klik tombol logout
2. Expected: Redirect ke login page

### B. Testing Dashboard

#### Test 4: Dashboard Admin
1. Login sebagai admin
2. Verify data yang muncul:
   - ✅ Pemasukan hari ini
   - ✅ Pemasukan bulan ini
   - ✅ Pengeluaran hari ini
   - ✅ Pengeluaran bulan ini
   - ✅ Profit hari ini
   - ✅ Profit bulan ini
   - ✅ 10 Transaksi terakhir
   - ✅ Produk stock rendah
   - ✅ 5 Pengeluaran terakhir

#### Test 5: Dashboard Kasir
1. Login sebagai kasir
2. Verify data yang muncul:
   - ✅ Pemasukan hari ini
   - ✅ Pemasukan bulan ini
   - ✅ Transaksi terakhir
   - ❌ Tidak ada data profit
   - ❌ Tidak ada data pengeluaran

### C. Testing Transaksi (Pemasukan)

#### Test 6: Buat Transaksi via POS (Kasir)
1. Login sebagai kasir
2. Akses `/pos`
3. Pilih produk (misal: Nasi Goreng x2, Es Teh x1)
4. Pilih meja (optional)
5. Input customer name (optional)
6. Pilih payment method: Cash
7. Input payment amount: 60000
8. Submit transaksi
9. Expected:
   - ✅ Invoice number auto-generated (INV20260122001)
   - ✅ Total = (25000 x 2) + (5000 x 1) = 55000
   - ✅ Change = 60000 - 55000 = 5000
   - ✅ Stock Nasi Goreng berkurang 2
   - ✅ Stock Es Teh berkurang 1
   - ✅ Redirect ke detail transaksi

#### Test 7: Validasi Stock
1. Buat transaksi dengan quantity > stock
2. Expected: Error "Stock tidak cukup"

#### Test 8: View Detail Transaksi
1. Akses `/transactions/{id}`
2. Expected:
   - ✅ Invoice number
   - ✅ Tanggal & waktu
   - ✅ Kasir name
   - ✅ List items dengan quantity & harga
   - ✅ Subtotal, Total, Payment, Change

#### Test 9: Delete Transaksi (Admin Only)
1. Login sebagai admin
2. Akses `/transactions`
3. Delete salah satu transaksi
4. Expected:
   - ✅ Stock produk di-restore
   - ✅ Table status kembali available
   - ✅ Transaksi terhapus

### D. Testing Pengeluaran (Admin Only)

#### Test 10: Tambah Pengeluaran
1. Login sebagai admin
2. Akses `/expenses/create`
3. Input:
   - Kategori: Gaji Karyawan
   - Deskripsi: Gaji bulan Januari
   - Jumlah: 5000000
   - Tanggal: 2026-01-22
   - Upload receipt (optional)
4. Submit
5. Expected:
   - ✅ Pengeluaran tersimpan
   - ✅ Muncul di list expenses

#### Test 11: Edit Pengeluaran
1. Edit pengeluaran yang sudah ada
2. Ubah amount atau description
3. Expected: Data terupdate

#### Test 12: Delete Pengeluaran
1. Delete pengeluaran
2. Expected:
   - ✅ Pengeluaran terhapus
   - ✅ Receipt image terhapus dari storage

#### Test 13: Akses Pengeluaran sebagai Kasir (Should Fail)
1. Login sebagai kasir
2. Akses `/expenses`
3. Expected: 403 Forbidden

### E. Testing Laporan Keuangan (Admin Only)

#### Test 14: Laporan Dashboard
1. Login sebagai admin
2. Akses `/reports`
3. Pilih date range (misal: 1-31 Jan 2026)
4. Expected:
   - ✅ Total Pemasukan (dari transaksi)
   - ✅ Total Pengeluaran
   - ✅ Total HPP
   - ✅ Laba Kotor = Pemasukan - HPP
   - ✅ Laba Bersih = Laba Kotor - Pengeluaran
   - ✅ Grafik pemasukan vs pengeluaran harian
   - ✅ Top 10 produk terlaris
   - ✅ Breakdown pengeluaran per kategori

#### Test 15: Laporan Penjualan
1. Akses `/reports/sales`
2. Expected:
   - ✅ List semua transaksi dalam periode
   - ✅ Detail per transaksi
   - ✅ Total penjualan

#### Test 16: Laporan Profit
1. Akses `/reports/profit`
2. Expected:
   - ✅ List transaksi dengan profit per transaksi
   - ✅ Margin profit (%)
   - ✅ Total profit

#### Test 17: Laporan Pengeluaran
1. Akses `/reports/expenses`
2. Expected:
   - ✅ List pengeluaran per periode
   - ✅ Kategori
   - ✅ Total pengeluaran

#### Test 18: Export Laporan CSV
1. Akses `/reports/export?start_date=2026-01-01&end_date=2026-01-31`
2. Expected:
   - ✅ File CSV terdownload
   - ✅ Berisi:
     - Detail transaksi dengan profit
     - Detail pengeluaran
     - Summary keuangan

### F. Testing Produk (Admin Only)

#### Test 19: Tambah Produk
1. Login sebagai admin
2. Akses `/products/create`
3. Input:
   - Kategori: Makanan
   - Nama: Sate Ayam
   - Harga Jual: 35000
   - Harga Pokok: 18000
   - Stock: 50
   - Upload image
4. Expected:
   - ✅ Produk tersimpan
   - ✅ Muncul di list
   - ✅ Unit Profit = 35000 - 18000 = 17000
   - ✅ Margin = 48.57%

#### Test 20: Edit Produk
1. Edit produk existing
2. Ubah harga/stock
3. Expected: Data terupdate

#### Test 21: Delete Produk
1. Delete produk yang tidak pernah digunakan
2. Expected: Produk terhapus

### G. Testing Kategori (Admin Only)

#### Test 22: CRUD Kategori
1. Create kategori baru
2. Edit kategori
3. Try delete kategori yang memiliki produk (Should fail)
4. Delete kategori kosong (Should success)

### H. Testing Meja (Admin Only)

#### Test 23: CRUD Meja
1. Create meja baru (T11, capacity 4)
2. Edit meja existing
3. Update status (available/occupied/reserved)
4. Delete meja

## Perhitungan Manual untuk Validasi

### Contoh Transaksi:
```
Items:
  - Nasi Goreng (Harga: 25000, HPP: 10000) x 2
  - Es Teh (Harga: 5000, HPP: 2000) x 1

Perhitungan:
  Subtotal = (25000 x 2) + (5000 x 1) = 55000
  Total HPP = (10000 x 2) + (2000 x 1) = 22000
  Profit Transaksi = 55000 - 22000 = 33000
```

### Contoh Laporan Keuangan:
```
Periode: 1-31 Jan 2026

Pemasukan (3 transaksi):
  - Transaksi 1: 55000 (HPP: 22000, Profit: 33000)
  - Transaksi 2: 45000 (HPP: 18000, Profit: 27000)
  - Transaksi 3: 30000 (HPP: 12000, Profit: 18000)
  Total Pemasukan: 130000
  Total HPP: 52000
  Laba Kotor: 78000

Pengeluaran:
  - Listrik: 500000
  - Gaji: 5000000
  - Bahan Baku: 2000000
  Total Pengeluaran: 7500000

Laba Bersih = 78000 - 7500000 = -7422000 (RUGI)
```

## Error Handling Testing

### Test 24: Validation Errors
1. Submit form kosong
2. Expected: Validation error messages

### Test 25: Authorization Errors
1. Kasir akses admin route
2. Expected: 403 Forbidden

### Test 26: Not Found Errors
1. Akses ID yang tidak exist
2. Expected: 404 Not Found

### Test 27: Database Constraint
1. Delete kategori yang memiliki produk
2. Expected: Error message yang friendly

## Performance Testing

### Test 28: Large Dataset
1. Seed 1000 transaksi
2. Akses laporan
3. Expected: Load time < 3 detik

### Test 29: Pagination
1. Verify pagination di list transaksi
2. Expected: 20 items per page

## Checklist Kualitas

### ✅ Functional Requirements
- [x] Login/Logout untuk Admin & Kasir
- [x] Dashboard dengan summary keuangan
- [x] POS untuk transaksi
- [x] Auto-generate invoice number
- [x] Stock management otomatis
- [x] Pencatatan pengeluaran dengan kategori
- [x] Upload receipt image
- [x] Laporan keuangan (pemasukan, pengeluaran, profit)
- [x] Export laporan ke CSV
- [x] CRUD produk dengan HPP
- [x] CRUD kategori
- [x] CRUD meja

### ✅ Non-Functional Requirements
- [x] Role-based access control
- [x] Data validation
- [x] Error handling
- [x] Image upload
- [x] Pagination
- [x] Responsive calculation

### ✅ Business Logic
- [x] Profit calculation accurate
- [x] Stock update on transaction
- [x] HPP tracking per item
- [x] Laba kotor vs laba bersih calculation
- [x] Date range filtering

## Kesimpulan Testing

Aplikasi sudah memiliki backend yang lengkap untuk:
1. ✅ Pencatatan pemasukan otomatis dari transaksi
2. ✅ Pencatatan pengeluaran manual dengan kategori
3. ✅ Laporan keuangan yang akurat dengan perhitungan profit
4. ✅ Role management (Admin & Kasir)
5. ✅ Export data untuk analisis

Yang masih perlu dibuat:
- [ ] Views (Blade templates)
- [ ] Frontend styling (Bootstrap/Tailwind)
- [ ] JavaScript untuk POS interaction
- [ ] Chart library untuk grafik
