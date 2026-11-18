# Development Guide

## 🛠️ Setup Development Environment

### Requirements
- PHP >= 8.2
- Composer
- SQLite3
- Git (optional)

### Installation
```bash
# Clone atau download project
cd Hosit-hosit

# Option 1: Using install script
bash install.sh

# Option 2: Manual installation
composer install
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve
```

## 📂 Project Structure

```
Hosit-hosit/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # All business logic
│   │   └── Middleware/       # AdminMiddleware
│   └── Models/               # Eloquent models
│
├── database/
│   ├── migrations/           # Database schema
│   ├── seeders/              # Sample data
│   └── database.sqlite       # SQLite database file
│
├── routes/
│   └── web.php              # All routes defined here
│
├── config/                   # Laravel config files
├── public/                   # Public assets
├── resources/
│   └── views/               # Blade templates (TO BE CREATED)
│
└── storage/
    └── app/public/          # Uploaded files
        ├── products/        # Product images
        └── receipts/        # Expense receipts
```

## 🔄 Development Workflow

### 1. Adding New Features

#### Add New Model
```bash
php artisan make:model ModelName -m
# -m flag creates migration too
```

#### Add New Controller
```bash
php artisan make:controller ControllerName --resource
# --resource flag creates CRUD methods
```

#### Add New Migration
```bash
php artisan make:migration create_table_name_table
```

### 2. Database Operations

#### Reset Database
```bash
php artisan migrate:fresh --seed
# Fresh install + seed data
```

#### Rollback Last Migration
```bash
php artisan migrate:rollback
```

#### Check Database
```bash
php artisan db:show
php artisan db:table users
```

### 3. Debugging

#### Enable Debug Mode
Edit `.env`:
```env
APP_DEBUG=true
APP_ENV=local
```

#### View Logs
```bash
tail -f storage/logs/laravel.log
```

#### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 🧪 Testing

### Manual Testing
Follow scenarios in `TESTING.md`

### Create Test
```bash
php artisan make:test TransactionTest
```

### Run Tests (when created)
```bash
php artisan test
```

## 🎨 Adding Views (Next Step)

### Create Layout
1. Create `resources/views/layouts/app.blade.php`:
```blade
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - Restaurant System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('layouts.sidebar')
    
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
```

### Create Login View
2. Create `resources/views/auth/login.blade.php`:
```blade
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <h2>Login</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email">
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</div>
@endsection
```

### Create Dashboard View
3. Create `resources/views/dashboard/index.blade.php` with data from controller

## 🔐 Security Checklist

- [x] Password hashing (bcrypt)
- [x] CSRF protection (Laravel default)
- [x] SQL injection protection (Eloquent ORM)
- [x] XSS protection (Blade {{ }} escaping)
- [x] Role-based access control
- [ ] Rate limiting (can be added)
- [ ] Input sanitization (add HTML Purifier if needed)

## 📝 Code Style

### Follow Laravel Best Practices
```php
// Good: Eloquent relationships
$user->transactions()->where('status', 'completed')->get();

// Bad: Manual joins
DB::table('users')->join('transactions', ...);

// Good: Route model binding
public function show(Transaction $transaction)

// Bad: Manual find
public function show($id) {
    $transaction = Transaction::find($id);
}
```

### Naming Conventions
- Models: Singular (User, Transaction)
- Tables: Plural (users, transactions)
- Controllers: Singular + Controller (UserController)
- Variables: camelCase ($totalIncome)
- Methods: camelCase (calculateProfit())

## 🚀 Deployment Checklist

### Before Deploy
1. Set environment to production:
```env
APP_ENV=production
APP_DEBUG=false
```

2. Generate production key:
```bash
php artisan key:generate
```

3. Cache configuration:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

4. Optimize autoloader:
```bash
composer install --optimize-autoloader --no-dev
```

5. Set proper permissions:
```bash
chmod -R 755 storage bootstrap/cache
```

### Production Server Requirements
- PHP >= 8.2
- Extensions: PDO, SQLite, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON
- Web server: Apache/Nginx
- HTTPS certificate (recommended)

## 🔧 Common Commands

### Artisan Commands
```bash
# List all commands
php artisan list

# List all routes
php artisan route:list

# Create custom command
php artisan make:command CommandName

# Run command
php artisan command:name
```

### Composer Commands
```bash
# Update dependencies
composer update

# Require new package
composer require package/name

# Dump autoload
composer dump-autoload
```

## 📊 Database Queries

### Useful Queries for Development

#### Check total pemasukan hari ini
```php
$today = Carbon::today();
$income = Transaction::whereDate('created_at', $today)
    ->where('status', 'completed')
    ->sum('total');
```

#### Check produk terlaris
```php
$topProducts = TransactionItem::select('product_name', DB::raw('SUM(quantity) as total_qty'))
    ->groupBy('product_name')
    ->orderBy('total_qty', 'desc')
    ->limit(10)
    ->get();
```

#### Check profit per transaksi
```php
$transactions = Transaction::with('items')->get();
foreach ($transactions as $transaction) {
    $profit = $transaction->total_profit; // accessor dari model
}
```

## 🐛 Troubleshooting

### Problem: "Class not found"
```bash
composer dump-autoload
```

### Problem: "Migration table not found"
```bash
php artisan migrate:install
php artisan migrate
```

### Problem: "Storage link not working"
```bash
rm public/storage
php artisan storage:link
```

### Problem: "Permission denied"
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 📚 Learning Resources

### Laravel Documentation
- [Laravel Docs](https://laravel.com/docs)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [Blade Templates](https://laravel.com/docs/blade)

### This Project
- README.md - Project overview
- DOCUMENTATION.md - Technical details
- TESTING.md - Testing guide
- FITUR_KEUANGAN.md - Financial features

## 🎯 Next Development Tasks

### High Priority
1. [ ] Create all Blade views
2. [ ] Add Bootstrap/Tailwind CSS
3. [ ] Create POS interface with JavaScript
4. [ ] Add chart visualization (Chart.js)

### Medium Priority
1. [ ] Print invoice functionality
2. [ ] Email notifications
3. [ ] Automated backup
4. [ ] User management CRUD

### Low Priority
1. [ ] REST API
2. [ ] Mobile app
3. [ ] Multi-language support
4. [ ] Advanced analytics

## 💡 Tips

1. **Use Laravel Debugbar** for development:
```bash
composer require barryvdh/laravel-debugbar --dev
```

2. **Use Laravel IDE Helper** for better autocomplete:
```bash
composer require --dev barryvdh/laravel-ide-helper
```

3. **Keep .env secure** - Never commit to git:
```bash
# Already in .gitignore
.env
```

4. **Regular backups** of database.sqlite:
```bash
cp database/database.sqlite database/backup_$(date +%Y%m%d).sqlite
```

5. **Use transactions** for critical operations:
```php
DB::transaction(function () {
    // Your code here
});
```

## 🤝 Contributing

### If working in team:

1. Use Git branches:
```bash
git checkout -b feature/new-feature
```

2. Follow commit message format:
```
feat: Add expense category filter
fix: Correct profit calculation
docs: Update API documentation
```

3. Pull request checklist:
- [ ] Code follows PSR standards
- [ ] All tests pass
- [ ] Documentation updated
- [ ] No console errors

---

Happy Coding! 🚀
