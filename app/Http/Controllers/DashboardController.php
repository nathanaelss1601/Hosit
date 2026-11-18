<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Expense;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Data untuk hari ini
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        // Pemasukan (Transaksi)
        $todayIncome = Transaction::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total');
            
        $monthlyIncome = Transaction::where('created_at', '>=', $thisMonth)
            ->where('status', 'completed')
            ->sum('total');
        
        // Pengeluaran
        $todayExpenses = Expense::whereDate('expense_date', $today)->sum('amount');
        $monthlyExpenses = Expense::where('expense_date', '>=', $thisMonth)->sum('amount');
        
        // Profit (hanya untuk admin, karena perlu data cost)
        $todayProfit = 0;
        $monthlyProfit = 0;
        
        if ($user->isAdmin()) {
            $todayTransactions = Transaction::with('items')
                ->whereDate('created_at', $today)
                ->where('status', 'completed')
                ->get();
            $todayProfit = $todayTransactions->sum('total_profit') - $todayExpenses;
            
            $monthlyTransactions = Transaction::with('items')
                ->where('created_at', '>=', $thisMonth)
                ->where('status', 'completed')
                ->get();
            $monthlyProfit = $monthlyTransactions->sum('total_profit') - $monthlyExpenses;
        }
        
        // Recent transactions
        $recentTransactions = Transaction::with(['user', 'items'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Recent expenses (admin only)
        $recentExpenses = [];
        if ($user->isAdmin()) {
            $recentExpenses = Expense::with('user')
                ->orderBy('expense_date', 'desc')
                ->limit(5)
                ->get();
        }
        
        return view('dashboard.index', compact(
            'todayIncome',
            'monthlyIncome',
            'todayExpenses',
            'monthlyExpenses',
            'todayProfit',
            'monthlyProfit',
            'recentTransactions',
            'recentExpenses'
        ));
    }
}
