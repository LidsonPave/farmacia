<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicineRequest;
use App\Http\Requests\UpdateMedicineRequest;
use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicineController extends Controller
{
    public function index(Request $request): View
    {
        $query = Medicine::query()->with('category');

        if ($search = $request->string('search')->trim()->value()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($request->input('stock_status') === 'baixo') {
            $query->whereColumn('stock_quantity', '<=', 'minimum_stock');
        }

        if ($request->input('expiry_status') === 'expirado') {
            $query->whereNotNull('expiry_date')->where('expiry_date', '<', now());
        } elseif ($request->input('expiry_status') === 'proximo') {
            $query->whereNotNull('expiry_date')
                ->whereBetween('expiry_date', [now(), now()->addDays(Medicine::EXPIRY_WARNING_DAYS)]);
        }

        $medicines = $query->orderBy('name')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('medicines.index', [
            'medicines' => $medicines,
            'categories' => $categories,
            'nextCode' => Medicine::generateNextCode(),
        ]);
    }

    public function store(StoreMedicineRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['code'] = $data['code'] ?? Medicine::generateNextCode();
        $data['status'] = 'ativo';

        Medicine::create($data);

        return redirect()->route('medicines.index')->with('success', 'Medicamento cadastrado com sucesso.');
    }

    public function update(UpdateMedicineRequest $request, Medicine $medicine): RedirectResponse
    {
        $medicine->update($request->validated());

        return redirect()->route('medicines.index')->with('success', 'Medicamento atualizado com sucesso.');
    }

    public function destroy(Medicine $medicine): RedirectResponse
    {
        $hasHistory = $medicine->stockMovements()->exists() || $medicine->saleItems()->exists();

        if ($hasHistory) {
            $medicine->update(['status' => 'inativo']);

            return redirect()->route('medicines.index')->with('success', 'Medicamento possui histórico associado e foi desativado em vez de eliminado.');
        }

        $medicine->delete();

        return redirect()->route('medicines.index')->with('success', 'Medicamento eliminado com sucesso.');
    }
}
