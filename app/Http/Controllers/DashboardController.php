<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Sale;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $medicines = Medicine::where('status', 'ativo')->get();

        $lowStockCount = $medicines->filter(fn (Medicine $medicine) => $medicine->isLowStock())->count();
        $nearExpiryCount = $medicines->filter(fn (Medicine $medicine) => $medicine->isNearExpiry())->count();
        $expiredCount = $medicines->filter(fn (Medicine $medicine) => $medicine->isExpired())->count();

        $salesToday = Sale::whereDate('sold_at', today())->sum('total');
        $salesThisMonth = Sale::whereYear('sold_at', now()->year)
            ->whereMonth('sold_at', now()->month)
            ->sum('total');

        $hasAnySale = Sale::exists();

        return view('dashboard', [
            'totalMedicines' => $medicines->count(),
            'lowStockCount' => $lowStockCount,
            'nearExpiryCount' => $nearExpiryCount,
            'expiredCount' => $expiredCount,
            'salesToday' => $salesToday,
            'salesThisMonth' => $salesThisMonth,
            'hasAnySale' => $hasAnySale,
        ]);
    }
}
