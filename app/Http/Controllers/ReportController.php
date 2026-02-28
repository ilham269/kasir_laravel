<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'date' => 'nullable|date',
            'month' => 'nullable|date_format:Y-m',
        ]);

        $selectedDate = $validated['date'] ?? now()->toDateString();
        $selectedMonth = $validated['month'] ?? now()->format('Y-m');

        $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth);
        $monthYear = (int) $monthDate->format('Y');
        $monthNumber = (int) $monthDate->format('m');

        $dailyTransactions = Transaction::with('user')
            ->whereDate('transaction_date', $selectedDate)
            ->latest('transaction_date')
            ->get();

        $monthlyTransactions = Transaction::with('user')
            ->whereYear('transaction_date', $monthYear)
            ->whereMonth('transaction_date', $monthNumber)
            ->latest('transaction_date')
            ->get();

        $bestSellingProducts = TransactionDetail::query()
            ->join('products', 'products.id', '=', 'transaction_details.product_id')
            ->select([
                'products.id as product_id',
                'products.name as product_name',
                DB::raw('SUM(transaction_details.quantity) as total_qty'),
                DB::raw('SUM(transaction_details.subtotal) as total_sales'),
            ])
            ->whereYear('transaction_details.created_at', $monthYear)
            ->whereMonth('transaction_details.created_at', $monthNumber)
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        $dailyTotal = (float) $dailyTransactions->sum('total_amount');
        $monthlyTotal = (float) $monthlyTransactions->sum('total_amount');
        $allTimeTotal = (float) Transaction::query()->sum('total_amount');

        return view('reports.index', [
            'selectedDate' => $selectedDate,
            'selectedMonth' => $selectedMonth,
            'dailyTransactions' => $dailyTransactions,
            'monthlyTransactions' => $monthlyTransactions,
            'bestSellingProducts' => $bestSellingProducts,
            'dailyTotal' => $dailyTotal,
            'monthlyTotal' => $monthlyTotal,
            'allTimeTotal' => $allTimeTotal,
        ]);
    }
}
