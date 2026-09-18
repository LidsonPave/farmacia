<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Models\Medicine;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Sale::query()->with('user');

        if ($paymentMethod = $request->input('payment_method')) {
            $query->where('payment_method', $paymentMethod);
        }

        $sales = $query->latest('sold_at')->paginate(15)->withQueryString();

        return view('vendas.index', [
            'sales' => $sales,
        ]);
    }

    public function create(): View
    {
        $medicines = Medicine::where('status', 'ativo')
            ->where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get(['id', 'code', 'name', 'sale_price', 'stock_quantity']);

        return view('vendas.create', [
            'medicines' => $medicines,
        ]);
    }

    public function store(StoreSaleRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $sale = DB::transaction(function () use ($data) {
            $subtotal = 0;
            $medicines = [];

            foreach ($data['items'] as $item) {
                $medicine = Medicine::findOrFail($item['medicine_id']);
                $medicines[] = ['medicine' => $medicine, 'quantity' => $item['quantity']];
                $subtotal += $medicine->sale_price * $item['quantity'];
            }

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'discount_type' => 'none',
                'discount_value' => null,
                'discount_amount' => 0,
                'total' => $subtotal,
                'payment_method' => $data['payment_method'],
                'sold_at' => now(),
            ]);

            foreach ($medicines as $entry) {
                $medicine = $entry['medicine'];
                $quantity = $entry['quantity'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $medicine->id,
                    'quantity' => $quantity,
                    'unit_price' => $medicine->sale_price,
                    'subtotal' => $medicine->sale_price * $quantity,
                ]);

                StockMovement::registerMovement([
                    'medicine_id' => $medicine->id,
                    'user_id' => auth()->id(),
                    'type' => 'saida',
                    'quantity' => $quantity,
                    'reason' => 'venda',
                    'reference' => "Venda #{$sale->id}",
                ]);
            }

            return $sale;
        });

        return redirect()->route('vendas.show', $sale)->with('success', 'Venda finalizada com sucesso.');
    }

    public function show(Sale $sale): View
    {
        $sale->load(['items.medicine', 'user']);

        return view('vendas.show', [
            'sale' => $sale,
        ]);
    }
}
