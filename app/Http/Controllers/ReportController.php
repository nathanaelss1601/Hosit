<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Expense;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        [$startDate, $endDate] = $this->getReportDateRange($request);
        
        // Pemasukan (Total Penjualan)
        $totalIncome = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total');
        
        // Pengeluaran
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');
        
        // Laba bersih kasar: Pemasukan - Pengeluaran
        $netProfit = $totalIncome - $totalExpenses;
        
        // Grafik Pemasukan vs Pengeluaran per hari
        $dailyData = $this->getDailyFinancialData($startDate, $endDate);
        
        // Top selling products
        $topProducts = TransactionItem::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->where('status', 'completed');
            })
            ->groupBy('product_name')
            ->orderBy('total_sales', 'desc')
            ->limit(10)
            ->get();
        
        // Expense by category
        $expensesByCategory = Expense::select('category', DB::raw('SUM(amount) as total'))
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();
        
        return view('reports.index', compact(
            'startDate',
            'endDate',
            'totalIncome',
            'totalExpenses',
            'netProfit',
            'dailyData',
            'topProducts',
            'expensesByCategory'
        ));
    }
    
    public function sales(Request $request)
    {
        [$startDate, $endDate] = $this->getReportDateRange($request);
        
        $transactions = Transaction::with(['user', 'items'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $totalSales = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total');
        
        return view('reports.sales', compact('transactions', 'startDate', 'endDate', 'totalSales'));
    }
    
    public function profit(Request $request)
    {
        [$startDate, $endDate] = $this->getReportDateRange($request);
        
        $totalIncome = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total');
        
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');
        
        $netProfit = $totalIncome - $totalExpenses;
        $dailyData = $this->getDailyFinancialData($startDate, $endDate);
        
        return view('reports.profit', compact(
            'startDate',
            'endDate',
            'totalIncome',
            'totalExpenses',
            'netProfit',
            'dailyData'
        ));
    }
    
    public function expenses(Request $request)
    {
        [$startDate, $endDate] = $this->getReportDateRange($request);
        
        $expenses = Expense::with('user')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->orderBy('expense_date', 'desc')
            ->paginate(20);
        
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');
        
        return view('reports.expenses', compact('expenses', 'startDate', 'endDate', 'totalExpenses'));
    }
    
    private function getReportDateRange(Request $request): array
    {
        $defaultStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        $defaultEnd = Carbon::now()->format('Y-m-d');

        if (!$request->hasAny(['start_date', 'end_date'])) {
            return [$defaultStart, $defaultEnd];
        }

        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ], [
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.required' => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal mulai tidak boleh lebih dari tanggal selesai.',
        ]);

        return [$validated['start_date'], $validated['end_date']];
    }

    private function getDailyFinancialData($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $dailyData = [];
        
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dayStr = $date->format('Y-m-d');
            
            $income = Transaction::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total');
            
            $expense = Expense::whereDate('expense_date', $date)
                ->sum('amount');
            
            $dailyData[] = [
                'date' => $date->format('d M'),
                'income' => $income,
                'expense' => $expense,
                'profit' => $income - $expense,
            ];
        }
        
        return $dailyData;
    }
    
    public function export(Request $request)
    {
        [$startDate, $endDate] = $this->getReportDateRange($request);
        
        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->get();
        
        $totalIncome = $transactions->sum('total');
        $totalExpenses = $expenses->sum('amount');
        $netProfit = $totalIncome - $totalExpenses;
        
        $filename = "laporan_keuangan_{$startDate}_to_{$endDate}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($transactions, $expenses, $totalIncome, $totalExpenses, $netProfit) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['LAPORAN KEUANGAN']);
            fputcsv($file, ['']);
            
            // Transactions
            fputcsv($file, ['PEMASUKAN (TRANSAKSI)']);
            fputcsv($file, ['Invoice', 'Tanggal', 'Total']);
            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->invoice_number,
                    $t->created_at->format('Y-m-d H:i'),
                    $t->total,
                ]);
            }
            
            fputcsv($file, ['']);
            
            // Expenses
            fputcsv($file, ['PENGELUARAN']);
            fputcsv($file, ['Tanggal', 'Kategori', 'Deskripsi', 'Jumlah']);
            foreach ($expenses as $e) {
                fputcsv($file, [
                    $e->expense_date->format('Y-m-d'),
                    $e->category,
                    $e->description,
                    $e->amount
                ]);
            }
            
            fputcsv($file, ['']);
            fputcsv($file, ['RINGKASAN']);
            fputcsv($file, ['Total Pemasukan', $totalIncome]);
            fputcsv($file, ['Total Pengeluaran', $totalExpenses]);
            fputcsv($file, ['Laba Bersih', $netProfit]);
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
