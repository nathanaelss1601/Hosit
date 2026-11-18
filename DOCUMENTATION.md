# Panduan Penggunaan Sistem Manajemen Restoran

## Daftar Isi
1. [Struktur Project](#struktur-project)
2. [Models & Relasi](#models--relasi)
3. [Controllers](#controllers)
4. [Routes](#routes)
5. [API Endpoints](#api-endpoints)
6. [Database Schema](#database-schema)

## Struktur Project

```
Hosit-hosit/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Login/Logout
│   │   │   ├── DashboardController.php     # Dashboard dengan summary keuangan
│   │   │   ├── TransactionController.php   # Pencatatan pemasukan
│   │   │   ├── ExpenseController.php       # Pencatatan pengeluaran
│   │   │   ├── ReportController.php        # Laporan keuangan
│   │   │   ├── ProductController.php       # CRUD produk
│   │   │   ├── CategoryController.php      # CRUD kategori
│   │   │   └── TableController.php         # CRUD meja
│   │   └── Middleware/
│   │       └── AdminMiddleware.php         # Proteksi route admin
│   └── Models/
│       ├── User.php                        # Model user (admin/kasir)
│       ├── Transaction.php                 # Model transaksi (pemasukan)
│       ├── TransactionItem.php             # Detail item transaksi
│       ├── Expense.php                     # Model pengeluaran
│       ├── Product.php                     # Model produk
│       ├── Category.php                    # Model kategori
│       └── Table.php                       # Model meja
├── database/
│   ├── migrations/                         # File migrasi database
│   ├── seeders/
│   │   └── DatabaseSeeder.php              # Data awal
│   └── database.sqlite                     # Database SQLite
└── routes/
    └── web.php                             # Routing aplikasi
```

## Models & Relasi

### User Model
```php
Fillable: name, email, password, role, phone, is_active
Relations:
  - hasMany: transactions, expenses
Methods:
  - isAdmin(): Check if user is admin
  - isKasir(): Check if user is kasir
```

### Transaction Model (Pemasukan)
```php
Fillable: invoice_number, user_id, table_id, customer_name, subtotal, tax, discount, total, payment_method, payment_amount, change_amount, status, notes
Relations:
  - belongsTo: user, table
  - hasMany: items (TransactionItem)
Methods:
  - generateInvoiceNumber(): Generate invoice otomatis (INV20260122001)
  - getTotalProfitAttribute(): Hitung total profit transaksi
  - getTotalCostAttribute(): Hitung total HPP transaksi
```

### TransactionItem Model
```php
Fillable: transaction_id, product_id, product_name, quantity, price, cost, subtotal, notes
Relations:
  - belongsTo: transaction, product
Methods:
  - getProfitAttribute(): Hitung profit per item
```

### Expense Model (Pengeluaran)
```php
Fillable: user_id, category, description, amount, expense_date, receipt_image
Relations:
  - belongsTo: user
Methods:
  - getCategories(): Return array kategori pengeluaran
```

### Product Model
```php
Fillable: category_id, name, description, price, cost, stock, image, is_available
Relations:
  - belongsTo: category
  - hasMany: transactionItems
Methods:
  - getUnitProfitAttribute(): price - cost
  - getProfitMarginAttribute(): ((price - cost) / price) * 100
```

### Category Model
```php
Fillable: name, description, is_active
Relations:
  - hasMany: products
```

### Table Model
```php
Fillable: table_number, capacity, status
Relations:
  - hasMany: transactions
Methods:
  - isAvailable(): Check if table available
```

## Controllers

### AuthController
- `showLogin()`: Tampilkan halaman login
- `login()`: Proses login
- `logout()`: Proses logout

### DashboardController
- `index()`: Dashboard dengan data:
  - Pemasukan hari ini & bulan ini
  - Pengeluaran hari ini & bulan ini
  - Profit hari ini & bulan ini (admin only)
  - Recent transactions (10 terakhir)
  - Low stock products (admin only)
  - Recent expenses (admin only)

### TransactionController (Pencatatan Pemasukan)
- `index()`: List semua transaksi
- `pos()`: Halaman Point of Sale untuk kasir
- `createFromPos()`: Simpan transaksi dari POS
  - Validasi stock
  - Generate invoice number
  - Simpan transaksi & items
  - Update stock produk
  - Update status meja
  - Track HPP untuk analisis profit
- `show($id)`: Detail transaksi
- `destroy($id)`: Hapus transaksi (admin only)
  - Restore stock
  - Update status meja

### ExpenseController (Pencatatan Pengeluaran)
- `index()`: List semua pengeluaran
- `create()`: Form tambah pengeluaran
- `store()`: Simpan pengeluaran baru
  - Upload receipt image
- `show($id)`: Detail pengeluaran
- `edit($id)`: Form edit pengeluaran
- `update($id)`: Update pengeluaran
  - Handle replace image
- `destroy($id)`: Hapus pengeluaran
  - Delete image

### ReportController (Laporan Keuangan)
- `index()`: Dashboard laporan dengan:
  - Total pemasukan (penjualan)
  - Total pengeluaran
  - Total HPP
  - Laba kotor (Pemasukan - HPP)
  - Laba bersih (Laba kotor - Pengeluaran)
  - Grafik pemasukan vs pengeluaran harian
  - Top 10 produk terlaris
  - Pengeluaran per kategori
  
- `sales()`: Laporan penjualan detail
  - List transaksi per periode
  - Total penjualan
  
- `profit()`: Laporan profit detail
  - Profit per transaksi
  - Margin profit
  
- `expenses()`: Laporan pengeluaran detail
  - List pengeluaran per periode
  - Total pengeluaran
  
- `export()`: Export laporan ke CSV
  - Detail transaksi dengan profit
  - Detail pengeluaran
  - Summary keuangan

### ProductController (Admin Only)
Standard CRUD untuk produk dengan:
- Input harga jual dan HPP
- Upload image
- Stock management

### CategoryController (Admin Only)
Standard CRUD untuk kategori

### TableController (Admin Only)
Standard CRUD untuk meja restoran

## Routes

### Public Routes
```php
GET  /                    -> login page
GET  /login              -> login page
POST /login              -> process login
POST /logout             -> process logout
```

### Authenticated Routes (Kasir & Admin)
```php
GET  /dashboard          -> Dashboard
GET  /pos                -> Point of Sale
POST /transactions/create-from-pos -> Create transaction from POS
```

### Resource Routes (Kasir & Admin)
```php
transactions.*          -> Transaction CRUD
```

### Admin Only Routes
```php
products.*              -> Product CRUD
categories.*            -> Category CRUD
expenses.*              -> Expense CRUD
tables.*                -> Table CRUD

GET  /reports           -> Report dashboard
GET  /reports/sales     -> Sales report
GET  /reports/profit    -> Profit report
GET  /reports/expenses  -> Expense report
GET  /reports/export    -> Export CSV
```

## Database Schema

### users
- id, name, email, password, role, phone, is_active, timestamps

### categories
- id, name, description, is_active, timestamps

### products
- id, category_id, name, description, price, cost, stock, image, is_available, timestamps

### tables
- id, table_number, capacity, status, timestamps

### transactions (Pemasukan)
- id, invoice_number, user_id, table_id, customer_name, subtotal, tax, discount, total, payment_method, payment_amount, change_amount, status, notes, timestamps

### transaction_items
- id, transaction_id, product_id, product_name, quantity, price, cost, subtotal, notes, timestamps

### expenses (Pengeluaran)
- id, user_id, category, description, amount, expense_date, receipt_image, timestamps

## Konsep Keuangan

### Flow Pencatatan Pemasukan:
1. Kasir/Admin membuat transaksi di POS
2. Pilih produk, input quantity
3. System otomatis ambil:
   - Harga jual (price)
   - Harga pokok (cost)
4. Calculate:
   - Subtotal per item = price × quantity
   - Total transaksi = sum of subtotals
5. Simpan dengan tracking profit per item
6. Update stock otomatis

### Flow Pencatatan Pengeluaran:
1. Admin input pengeluaran
2. Pilih kategori
3. Input jumlah dan tanggal
4. Upload bukti (optional)
5. System simpan untuk laporan

### Perhitungan Laporan:
```
Pemasukan = Total semua transaksi completed
HPP = Total (quantity × cost) dari semua transaction items
Laba Kotor = Pemasukan - HPP
Pengeluaran = Total semua expense
Laba Bersih = Laba Kotor - Pengeluaran
```

### Fitur Analisis:
- Profit margin per produk
- Profit per transaksi
- Trend pemasukan vs pengeluaran harian
- Top products berdasarkan revenue
- Breakdown pengeluaran per kategori

## Next Steps

Untuk melengkapi aplikasi, Anda perlu membuat:

1. **Views (Blade Templates)**:
   - Auth views (login)
   - Dashboard view
   - POS view
   - Transaction views
   - Expense views
   - Report views
   - CRUD views untuk products, categories, tables

2. **Assets**:
   - CSS framework (Bootstrap/Tailwind)
   - JavaScript untuk POS interaction
   - Chart library untuk grafik laporan

3. **Optional Enhancements**:
   - Print invoice
   - Email notification
   - Backup database
   - Multi-currency support
   - Tax calculation
   - Discount system
