<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ValidityController extends Controller
{
    public function index(Request $request): View
    {
        $query = Medicine::query()->with('category')->where('status', 'ativo');

        if ($search = $request->string('search')->trim()->value()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $warningDate = now()->addDays(Medicine::EXPIRY_WARNING_DAYS);

        match ($request->input('status')) {
            'expirado' => $query->whereNotNull('expiry_date')->where('expiry_date', '<', now()),
            'proximo' => $query->whereNotNull('expiry_date')->whereBetween('expiry_date', [now(), $warningDate]),
            'normal' => $query->whereNotNull('expiry_date')->where('expiry_date', '>', $warningDate),
            'sem_validade' => $query->whereNull('expiry_date'),
            default => null,
        };

        $medicines = $query->orderByRaw('expiry_date IS NULL')->orderBy('expiry_date')->paginate(15)->withQueryString();

        return view('validade.index', [
            'medicines' => $medicines,
            'warningDays' => Medicine::EXPIRY_WARNING_DAYS,
        ]);
    }
}
