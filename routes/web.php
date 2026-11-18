<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmployeeController;

// Authentication Routes
Route::redirect('/', '/login');
Route::post('/', function () {
    return redirect()->route('login');
})->withoutMiddleware([VerifyCsrfToken::class]);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Transaction Routes (Kasir & Admin)
    Route::resource('transactions', TransactionController::class)->only(['index', 'show', 'destroy']);
    Route::get('/pos', [TransactionController::class, 'pos'])->name('pos');
    Route::post('/transactions/create-from-pos', [TransactionController::class, 'createFromPos'])->name('transactions.create-from-pos');
    
    // Admin Only Routes
    Route::middleware(['admin'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::get('expenses/{expense}/receipt', [ExpenseController::class, 'receipt'])->name('expenses.receipt');
        Route::resource('expenses', ExpenseController::class);

        // Attendance routes must be registered before employees resource
        Route::get('employees/attendance-log', [EmployeeController::class, 'attendanceLog'])->name('employees.attendance-log');
        Route::post('employees/attendance-log', [EmployeeController::class, 'storeAttendance'])->name('employees.attendance-store');
        Route::resource('employees', EmployeeController::class)->only(['index', 'show', 'create', 'store']);
        
        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/profit', [ReportController::class, 'profit'])->name('reports.profit');
        Route::get('/reports/expenses', [ReportController::class, 'expenses'])->name('reports.expenses');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    });
});

