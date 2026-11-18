<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('user')
            ->orderBy('expense_date', 'desc')
            ->paginate(20);
        
        $categories = Expense::getCategories();
        
        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $categories = Expense::getCategories();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'receipt_image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $validated['user_id'] = auth()->id();

        // Handle image upload
        if ($request->hasFile('receipt_image')) {
            $path = $request->file('receipt_image')->store('receipts', 'public');
            $validated['receipt_image'] = $path;
        }

        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function show($id)
    {
        $expense = Expense::with('user')->findOrFail($id);
        return view('expenses.show', compact('expense'));
    }

    public function receipt($id)
    {
        $expense = Expense::findOrFail($id);

        if (!$expense->receipt_image || !Storage::disk('public')->exists($expense->receipt_image)) {
            abort(404);
        }

        return Storage::disk('public')->response($expense->receipt_image);
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $categories = Expense::getCategories();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $validated = $request->validate([
            'category' => 'required|string',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'receipt_image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('receipt_image')) {
            // Delete old image
            if ($expense->receipt_image) {
                Storage::disk('public')->delete($expense->receipt_image);
            }
            
            $path = $request->file('receipt_image')->store('receipts', 'public');
            $validated['receipt_image'] = $path;
        }

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil diupdate!');
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);

        // Delete image if exists
        if ($expense->receipt_image) {
            Storage::disk('public')->delete($expense->receipt_image);
        }

        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil dihapus!');
    }
}
