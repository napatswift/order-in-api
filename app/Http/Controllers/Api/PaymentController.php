<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $this->authorize('viewAny', Payment::class);

        return Payment::all();
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);

        return new PaymentResource($payment);
    }

    public function store(StorePaymentRequest $request)
    {
        $this->authorize('create', Payment::class);

        $payment = new PaymentResource(Payment::create($request->all()));
        if ($payment->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment saved successfully',
                'payment' => $payment
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Payment saved failed'
        ], 500);
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        $payment->update($request->all());
        if ($payment->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully',
                'payment' => $payment
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Payment updated failed'
        ], 500);
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);
        
        if ($payment->delete()) {
            return response()->json([
                'success' => "Payment deleted successfully"
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => "Payment deleted failed"
        ], 500);
    }
}
