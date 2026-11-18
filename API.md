# API Specification (Future Enhancement)

Jika ingin menambahkan REST API untuk mobile app atau integrasi eksternal, berikut struktur yang disarankan:

## Authentication

```
POST /api/login
Body: { email, password }
Response: { token, user: { id, name, email, role } }

POST /api/logout
Headers: Authorization: Bearer {token}
Response: { message: "Logged out successfully" }
```

## Endpoints

### Dashboard
```
GET /api/dashboard
Headers: Authorization: Bearer {token}
Response: {
  today: {
    income: 500000,
    expenses: 150000,
    profit: 350000
  },
  monthly: {
    income: 15000000,
    expenses: 5000000,
    profit: 10000000
  },
  recent_transactions: [...],
  low_stock_products: [...] // admin only
}
```

### Transactions (Pemasukan)
```
GET /api/transactions
GET /api/transactions/{id}
POST /api/transactions
DELETE /api/transactions/{id} // admin only

POST /api/transactions
Body: {
  items: [
    { product_id: 1, quantity: 2, notes: "" },
    { product_id: 5, quantity: 1 }
  ],
  table_id: 3,
  customer_name: "John Doe",
  payment_method: "cash",
  payment_amount: 60000,
  notes: ""
}
Response: {
  transaction: {
    id: 123,
    invoice_number: "INV20260122001",
    total: 55000,
    change_amount: 5000,
    items: [...]
  }
}
```

### Expenses (Pengeluaran) - Admin Only
```
GET /api/expenses
GET /api/expenses/{id}
POST /api/expenses
PUT /api/expenses/{id}
DELETE /api/expenses/{id}

POST /api/expenses
Body: {
  category: "Gaji Karyawan",
  description: "Gaji bulan Januari",
  amount: 5000000,
  expense_date: "2026-01-22",
  receipt_image: File
}
```

### Reports - Admin Only
```
GET /api/reports/summary?start_date=2026-01-01&end_date=2026-01-31
Response: {
  total_income: 15000000,
  total_cost: 6000000,
  gross_profit: 9000000,
  total_expenses: 5000000,
  net_profit: 4000000,
  daily_data: [...],
  top_products: [...],
  expenses_by_category: [...]
}

GET /api/reports/sales?start_date=...&end_date=...
GET /api/reports/profit?start_date=...&end_date=...
GET /api/reports/expenses?start_date=...&end_date=...
GET /api/reports/export?start_date=...&end_date=... // CSV download
```

### Products - Admin Only
```
GET /api/products
GET /api/products/{id}
POST /api/products
PUT /api/products/{id}
DELETE /api/products/{id}

POST /api/products
Body: {
  category_id: 1,
  name: "Sate Ayam",
  description: "...",
  price: 35000,
  cost: 18000,
  stock: 50,
  image: File,
  is_available: true
}
```

### Categories - Admin Only
```
GET /api/categories
POST /api/categories
PUT /api/categories/{id}
DELETE /api/categories/{id}
```

### Tables - Admin Only
```
GET /api/tables
POST /api/tables
PUT /api/tables/{id}
DELETE /api/tables/{id}

PUT /api/tables/{id}/status
Body: { status: "occupied" }
```

## Error Responses

```json
{
  "error": true,
  "message": "Error message here",
  "code": 400
}
```

## Success Responses

```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

## Implementasi

Untuk mengimplementasikan API ini, tambahkan di `routes/api.php`:

```php
use Laravel\Sanctum\HasApiTokens;

// Di User model tambahkan:
use HasApiTokens;

// Routes:
Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [ApiDashboardController::class, 'index']);
    Route::apiResource('transactions', ApiTransactionController::class);
    
    // Admin only
    Route::middleware('admin')->group(function () {
        Route::apiResource('expenses', ApiExpenseController::class);
        Route::get('/reports/summary', [ApiReportController::class, 'summary']);
        Route::apiResource('products', ApiProductController::class);
    });
});
```

## Package yang Dibutuhkan

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```
