<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : today();

        $salesQuery = Sale::query()->whereDate('sold_at', $date);

        $totalSold = (clone $salesQuery)->sum('total');
        $totalSalesCount = (clone $salesQuery)->count();

        $byPaymentMethod = (clone $salesQuery)
            ->selectRaw('payment_method, SUM(total) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        $paymentMethods = ['dinheiro', 'mpesa', 'emola', 'cartao', 'outro'];
        $paymentTotals = collect($paymentMethods)->mapWithKeys(function ($method) use ($byPaymentMethod) {
            return [$method => $byPaymentMethod->get($method, 0)];
        });

        $topMedicines = SaleItem::query()
            ->whereHas('sale', fn ($q) => $q->whereDate('sold_at', $date))
            ->selectRaw('medicine_id, SUM(quantity) as total_quantity')
            ->groupBy('medicine_id')
            ->orderByDesc('total_quantity')
            ->with('medicine')
            ->limit(5)
            ->get();

        $activeMedicines = Medicine::where('status', 'ativo')->get();

        return view('relatorios.index', [
            'date' => $date,
            'totalSold' => $totalSold,
            'totalSalesCount' => $totalSalesCount,
            'paymentTotals' => $paymentTotals,
            'topMedicines' => $topMedicines,
            'lowStockCount' => $activeMedicines->filter(fn (Medicine $m) => $m->isLowStock())->count(),
            'nearExpiryCount' => $activeMedicines->filter(fn (Medicine $m) => $m->isNearExpiry())->count(),
            'expiredCount' => $activeMedicines->filter(fn (Medicine $m) => $m->isExpired())->count(),
        ]);
    }
}
