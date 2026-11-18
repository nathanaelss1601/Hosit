# Fitur Pencatatan Keuangan - Detail Lengkap

## 🎯 Tujuan Utama

Sistem ini dirancang khusus untuk menyelesaikan permasalahan:
1. **Pencatatan Pemasukan** yang akurat dari transaksi penjualan
2. **Pencatatan Pengeluaran** operasional restoran
3. **Laporan Keuangan** yang efisien untuk decision making

## 💰 A. PEMASUKAN (Revenue Tracking)

### Sumber Pemasukan
Pemasukan dicatat **otomatis** dari setiap transaksi penjualan yang dilakukan kasir.

### Cara Kerja
```
Kasir membuat transaksi → System:
1. Generate invoice number unik (INV20260122001)
2. Catat semua item yang dibeli
3. Untuk setiap item, simpan:
   - Harga Jual (price)
   - Harga Pokok/HPP (cost)
   - Quantity
   - Subtotal
4. Hitung total transaksi
5. Simpan payment method & amount
6. Update stock otomatis
7. Track profit per item
```

### Data yang Tercatat
| Field | Deskripsi | Contoh |
|-------|-----------|--------|
| invoice_number | Nomor invoice unik | INV20260122001 |
| user_id | Kasir yang input | 2 (Kasir 1) |
| customer_name | Nama customer (optional) | "John Doe" |
| subtotal | Total sebelum tax/discount | 55,000 |
| total | Total akhir | 55,000 |
| payment_method | Cara bayar | cash/card/qris |
| payment_amount | Jumlah yang dibayar | 60,000 |
| change_amount | Kembalian | 5,000 |

### Detail Item Transaksi
Setiap transaksi memiliki detail items:
```json
{
  "transaction_id": 123,
  "product_id": 1,
  "product_name": "Nasi Goreng",
  "quantity": 2,
  "price": 25000,     // Harga jual
  "cost": 10000,      // HPP (untuk hitung profit)
  "subtotal": 50000
}
```

**Penting:** Field `cost` (HPP) digunakan untuk menghitung profit:
```
Profit = (price - cost) × quantity
      = (25000 - 10000) × 2
      = 30,000
```

### Keunggulan Sistem
✅ **Otomatis** - Tidak perlu input manual  
✅ **Akurat** - Data real-time dari kasir  
✅ **Traceable** - Setiap rupiah bisa dilacak  
✅ **Stock Management** - Update stock otomatis  
✅ **Profit Tracking** - Tahu profit per transaksi  

## 💸 B. PENGELUARAN (Expense Tracking)

### Sumber Pengeluaran
Pengeluaran dicatat **manual** oleh Admin untuk semua biaya operasional.

### Kategori Pengeluaran
System menyediakan 10 kategori standar:
1. **Gaji Karyawan** - Payroll bulanan
2. **Listrik** - Tagihan PLN
3. **Air** - Tagihan PDAM
4. **Gas** - Gas untuk dapur
5. **Bahan Baku** - Pembelian bahan mentah
6. **Peralatan** - Alat masak, piring, dll
7. **Promosi & Marketing** - Iklan, banner, social media
8. **Sewa Tempat** - Sewa lokasi restoran
9. **Maintenance** - Perbaikan peralatan
10. **Lain-lain** - Kategori umum

### Cara Input Pengeluaran
```
Admin → Menu Expenses → Create New
1. Pilih Kategori (dropdown)
2. Input Deskripsi (detail pengeluaran)
3. Input Jumlah (Rp)
4. Pilih Tanggal Pengeluaran
5. Upload Bukti/Receipt (optional)
6. Submit
```

### Data yang Tercatat
| Field | Deskripsi | Contoh |
|-------|-----------|--------|
| category | Kategori pengeluaran | Gaji Karyawan |
| description | Detail lengkap | Gaji bulan Januari 2026 |
| amount | Jumlah rupiah | 5,000,000 |
| expense_date | Tanggal pengeluaran | 2026-01-22 |
| receipt_image | Bukti upload | receipts/abc123.jpg |
| user_id | Admin yang input | 1 |

### Upload Receipt
System support upload bukti pengeluaran:
- Format: JPG, PNG
- Max size: 2MB
- Disimpan di: `storage/app/public/receipts/`
- Bisa diview dan didownload

### Keunggulan Sistem
✅ **Kategorisasi** - Mudah analisis per kategori  
✅ **Dokumentasi** - Upload bukti pengeluaran  
✅ **Audit Trail** - Tahu siapa yang input  
✅ **Flexible Date** - Bisa backdate jika lupa input  

## 📊 C. LAPORAN KEUANGAN (Financial Reports)

### Jenis Laporan

#### 1. Dashboard Laporan
Akses: `/reports`

Menampilkan overview keuangan per periode:
```
Filter: Start Date → End Date

Summary:
├─ Total Pemasukan (Revenue)
├─ Total HPP (Cost of Goods Sold)
├─ Laba Kotor (Gross Profit)
├─ Total Pengeluaran (Expenses)
└─ Laba Bersih (Net Profit)

Visualisasi:
├─ Grafik Pemasukan vs Pengeluaran Harian
├─ Top 10 Produk Terlaris
└─ Breakdown Pengeluaran per Kategori
```

#### 2. Laporan Penjualan
Akses: `/reports/sales`

Detail semua transaksi:
```
Columns:
- Invoice Number
- Tanggal
- Kasir
- Customer
- Items
- Total
- Payment Method

Summary:
- Total Penjualan
- Jumlah Transaksi
- Average Transaction Value
```

#### 3. Laporan Profit
Akses: `/reports/profit`

Analisis profit per transaksi:
```
Columns:
- Invoice Number
- Tanggal
- Sales (Harga Jual)
- Cost (HPP)
- Profit
- Margin (%)

Summary:
- Total Sales
- Total Cost
- Total Profit
- Average Margin
```

#### 4. Laporan Pengeluaran
Akses: `/reports/expenses`

Detail semua pengeluaran:
```
Columns:
- Tanggal
- Kategori
- Deskripsi
- Jumlah
- Input By
- Receipt

Summary:
- Total Pengeluaran
- Per Kategori
```

### Perhitungan Keuangan

#### Formula Utama
```
1. Laba Kotor (Gross Profit)
   = Total Pemasukan - Total HPP
   = Revenue - COGS

2. Laba Bersih (Net Profit)
   = Laba Kotor - Total Pengeluaran
   = Gross Profit - Expenses
```

#### Contoh Perhitungan Lengkap

**Periode: 1-31 Januari 2026**

**A. PEMASUKAN**
```
Transaksi 1: Nasi Goreng x2, Es Teh x1
  Sales: (25,000 × 2) + (5,000 × 1) = 55,000
  HPP:   (10,000 × 2) + (2,000 × 1) = 22,000
  Profit: 33,000

Transaksi 2: Ayam Bakar x1, Kopi x1
  Sales: (30,000 × 1) + (10,000 × 1) = 40,000
  HPP:   (15,000 × 1) + (4,000 × 1)  = 19,000
  Profit: 21,000

Transaksi 3: Mie Goreng x3
  Sales: (20,000 × 3) = 60,000
  HPP:   (8,000 × 3)  = 24,000
  Profit: 36,000

Total Pemasukan: 155,000
Total HPP: 65,000
Laba Kotor: 90,000
```

**B. PENGELUARAN**
```
22 Jan: Listrik          - Rp    500,000
22 Jan: Air              - Rp    200,000
22 Jan: Gas              - Rp    300,000
25 Jan: Gaji Karyawan    - Rp  5,000,000
28 Jan: Bahan Baku       - Rp  2,000,000
30 Jan: Promosi          - Rp    500,000

Total Pengeluaran: Rp 8,500,000
```

**C. HASIL AKHIR**
```
Laba Kotor:     Rp     90,000
Pengeluaran:    Rp  8,500,000
─────────────────────────────
Laba Bersih:    Rp -8,410,000 (RUGI)
```

**Insight:**
- Revenue bagus (155k dari 3 transaksi)
- Profit margin tinggi (58%)
- Tapi pengeluaran terlalu besar (gaji 5jt)
- **Action:** Perlu tingkatkan penjualan atau kurangi expenses

### Export Laporan

**Format:** CSV (Excel-compatible)

**Isi File:**
```csv
LAPORAN KEUANGAN

PEMASUKAN (TRANSAKSI)
Invoice,Tanggal,Total,HPP,Profit
INV20260122001,2026-01-22,55000,22000,33000
INV20260122002,2026-01-22,40000,19000,21000

PENGELUARAN
Tanggal,Kategori,Deskripsi,Jumlah
2026-01-22,Listrik,PLN Januari,500000
2026-01-25,Gaji,Gaji Januari,5000000

RINGKASAN
Total Pemasukan,155000
Total HPP,65000
Laba Kotor,90000
Total Pengeluaran,8500000
Laba Bersih,-8410000
```

**Kegunaan:**
- Import ke Excel untuk analisis lebih lanjut
- Backup data keuangan
- Kirim ke akuntan
- Dokumentasi pajak

## 🎯 Workflow Harian

### Pagi (08:00)
```
□ Kasir login
□ Cek dashboard untuk starting point
□ Review stock produk
```

### Siang (12:00 - 14:00) - Rush Hour
```
□ Kasir handle transaksi via POS
□ System auto-record setiap transaksi
□ Stock otomatis berkurang
```

### Sore (17:00)
```
□ Admin login
□ Input pengeluaran hari ini:
  - Beli bahan baku pagi
  - Bayar supplier
  - Biaya lain-lain
□ Upload receipt
```

### Malam (21:00) - Review
```
□ Admin buka Reports
□ Lihat summary hari ini:
  - Pemasukan: Rp xxx
  - Pengeluaran: Rp xxx
  - Profit: Rp xxx
□ Check produk terlaris
□ Analisis untuk esok hari
```

### Akhir Bulan
```
□ Admin generate laporan bulanan
□ Export CSV
□ Review kategori pengeluaran terbesar
□ Planning budget bulan depan
□ Backup database
```

## 🔍 Analisis yang Bisa Dilakukan

### 1. Profit Margin Analysis
Lihat produk mana yang paling profitable:
```
Produk A: Margin 60% → Fokus jual ini
Produk B: Margin 20% → Pertimbangkan harga
Produk C: Margin -10% → STOP jual!
```

### 2. Expense Category Analysis
Kategori pengeluaran terbesar:
```
Gaji: 50% → Normal
Bahan Baku: 30% → Cari supplier lebih murah?
Listrik: 10% → Hemat energi
Marketing: 5% → Mungkin perlu dinaikkan
Lain-lain: 5% → OK
```

### 3. Daily Trend Analysis
Grafik harian untuk lihat pattern:
```
Senin: Sepi → Buat promo
Jumat-Sabtu: Ramai → Siapkan stock lebih
Tanggal 25: Spike pengeluaran → Gajian
```

### 4. Product Performance
Top products untuk planning menu:
```
Nasi Goreng: 100 porsi/bulan → Best seller
Es Teh: 80 porsi/bulan → Popular
Dessert: 5 porsi/bulan → Kurang laku, hapus?
```

## ✨ Keunggulan Sistem

### vs Pencatatan Manual (Buku)
| Aspect | Manual | System |
|--------|--------|--------|
| Input pemasukan | Manual tulis satu-satu | Otomatis dari transaksi |
| Hitung profit | Susah, perlu kalkulator | Otomatis real-time |
| Laporan | Bikin sendiri | Klik button |
| Export | Fotocopy | CSV instant |
| Analisis | Butuh waktu | Dashboard real-time |

### vs Excel Spreadsheet
| Aspect | Excel | System |
|--------|-------|--------|
| Entry | Manual double entry | Single entry |
| Error | Rumus bisa salah | Logic validated |
| Multi-user | Sulit | Role-based |
| Mobile | Tidak praktis | Bisa via API |
| Backup | Manual | Otomatis |

## 🎓 Tips Penggunaan

1. **Input HPP Akurat**
   - Selalu catat harga pokok saat beli bahan
   - Update HPP jika harga supplier naik
   - Profit analysis bergantung pada data ini

2. **Catat Pengeluaran Harian**
   - Jangan menumpuk di akhir bulan
   - Upload receipt untuk audit trail
   - Categorize dengan benar

3. **Review Laporan Mingguan**
   - Jangan tunggu akhir bulan
   - Deteksi masalah lebih cepat
   - Adjust strategy kalau perlu

4. **Backup Data Bulanan**
   - Export CSV setiap akhir bulan
   - Simpan di cloud (Google Drive)
   - Buat backup database SQLite

5. **Training Staff**
   - Kasir harus paham cara input transaksi
   - Admin harus rutin input pengeluaran
   - Review bersama setiap bulan

## 🚀 Kesimpulan

Sistem ini menyelesaikan 3 masalah utama:

1. ✅ **Pencatatan Pemasukan Efisien**
   - Otomatis dari transaksi
   - Tidak ada human error
   - Real-time tracking

2. ✅ **Pencatatan Pengeluaran Akurat**
   - Kategorisasi jelas
   - Dokumentasi lengkap
   - Easy to track

3. ✅ **Laporan Keuangan Efektif**
   - Laba bersih otomatis dihitung
   - Visual analysis dengan grafik
   - Export untuk further analysis

**Hasil:** Decision making berdasarkan data, bukan feeling! 📊
