<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function financial(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to',   now()->endOfMonth()->toDateString());

        $totalSales = Sale::whereBetween('date', [$from, $to])
            ->sum('net_amount') ?? 0;

        $totalVat = Sale::whereBetween('date', [$from, $to])
            ->sum('vat_amount') ?? 0;

        $totalRevenue = $totalSales + $totalVat;

        $totalExpense = JournalEntry::whereBetween('date', [$from, $to])
            ->where('account', 'cogs')
            ->sum('debit') ?? 0;

        $netProfit = $totalRevenue - $totalExpense;

        return Inertia::render('Reports/Financial', [
            'from'         => $from,
            'to'           => $to,
            'totalRevenue' => (float) $totalRevenue,
            'totalSales'   => (float) $totalSales,
            'totalVat'     => (float) $totalVat,
            'totalExpense' => (float) $totalExpense,
            'netProfit'    => (float) $netProfit,
        ]);
    }
}
