<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockMovementRequest;
use App\Models\Medicine;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    public function index(Request $request): View
    {
        $query = StockMovement::query()->with(['medicine', 'user']);

        if ($medicineId = $request->input('medicine_id')) {
            $query->where('medicine_id', $medicineId);
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($reason = $request->input('reason')) {
            $query->where('reason', $reason);
        }

        $movements = $query->latest()->paginate(15)->withQueryString();
        $medicines = Medicine::where('status', 'ativo')->orderBy('name')->get();

        return view('movimentacoes.index', [
            'movements' => $movements,
            'medicines' => $medicines,
        ]);
    }

    public function store(StoreStockMovementRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        StockMovement::registerMovement($data);

        return redirect()->route('movimentacoes.index')->with('success', 'Movimentação registada com sucesso.');
    }
}
