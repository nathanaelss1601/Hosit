<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('transactions.index', compact('transactions'));
    }

    public function pos()
    {
        $products = Product::with('category')
            ->where('is_available', true)
            ->get();
        
        return view('transactions.pos', compact('products'));
    }

    public function createFromPos(Request $request)
    {
        $validated = $request->validate([
            'items' => 'nullable|array',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.quantity' => 'nullable|integer|min:1',
            'customer_name' => 'nullable|string',
            'payment_method' => 'required|in:cash,card,qris,transfer',
            'payment_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'custom_description' => 'nullable|string',
            'custom_amount' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $itemsData = [];

            // Calculate subtotal and prepare items data from products
            if (!empty($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    if (empty($item['product_id'])) {
                        continue;
                    }
                    
                    $product = Product::findOrFail($item['product_id']);

                    $itemSubtotal = $product->price * $item['quantity'];
                    $subtotal += $itemSubtotal;

                    $itemsData[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'cost' => $product->cost,
                        'subtotal' => $itemSubtotal,
                        'notes' => $item['notes'] ?? null,
                    ];
                }
            }

            // Add custom income if provided
            $customAmount = (float) ($validated['custom_amount'] ?? 0);
            if ($customAmount > 0) {
                $subtotal += $customAmount;
                $itemsData[] = [
                    'product_id' => null,
                    'product_name' => $validated['custom_description'] ?? 'Pendapatan Custom',
                    'quantity' => 1,
                    'price' => $customAmount,
                    'cost' => 0,
                    'subtotal' => $customAmount,
                    'notes' => null,
                ];
            }

            // Validate that at least one item exists
            if (empty($itemsData)) {
                return back()->with('error', 'Pilih setidaknya satu produk atau masukkan pendapatan custom!')->withInput();
            }

            // Apply discount
            $discount = (float) ($validated['discount'] ?? 0);
            $total = $subtotal - $discount;

            if ($validated['payment_amount'] < $total) {
                return back()
                    ->with('error', 'Jumlah bayar lebih rendah dari total transaksi!')
                    ->withInput();
            }

            $changeAmount = $validated['payment_amount'] - $total;

            // Create transaction
            $transaction = Transaction::create([
                'invoice_number' => Transaction::generateInvoiceNumber(),
                'user_id' => auth()->id(),
                'customer_name' => $validated['customer_name'],
                'subtotal' => $subtotal,
                'tax' => 0,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'payment_amount' => $validated['payment_amount'],
                'change_amount' => $changeAmount,
                'status' => 'completed',
                'notes' => $validated['notes'],
            ]);

            // Create transaction items
            foreach ($itemsData as $itemData) {
                $transaction->items()->create($itemData);
            }
            

            DB::commit();

            return redirect()->route('transactions.show', $transaction->id)
                ->with('success', 'Transaksi berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $transaction = Transaction::with(['user', 'items.product'])
            ->findOrFail($id);
        
        return view('transactions.show', compact('transaction'));
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        // Only allow deletion if admin
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            // (No stock or table updates necessary after product/table removal)

            $transaction->delete();

            DB::commit();

            return redirect()->route('transactions.index')
                ->with('success', 'Transaksi berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
