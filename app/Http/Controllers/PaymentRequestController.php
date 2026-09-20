<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentRequestController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:mpesa,emola'],
        ]);

        $paymentRequest = PaymentRequest::create([
            'user_id' => auth()->id(),
            'phone' => $data['phone'],
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
        ]);

        return response()->json([
            'reference' => $paymentRequest->reference,
            'url' => route('pagamentos.show', $paymentRequest->reference),
        ]);
    }

    public function show(string $reference): View
    {
        $paymentRequest = PaymentRequest::where('reference', $reference)->firstOrFail();

        return view('pagamentos.show', [
            'paymentRequest' => $paymentRequest,
        ]);
    }

    public function confirm(Request $request, string $reference): JsonResponse
    {
        $request->validate([
            'pin' => ['required', 'digits:4'],
        ]);

        $paymentRequest = PaymentRequest::where('reference', $reference)->firstOrFail();

        if ($paymentRequest->status === 'pending') {
            $paymentRequest->update(['status' => 'approved']);
        }

        return response()->json(['status' => $paymentRequest->status]);
    }

    public function status(string $reference): JsonResponse
    {
        $paymentRequest = PaymentRequest::where('reference', $reference)->firstOrFail();

        return response()->json(['status' => $paymentRequest->status]);
    }
}
