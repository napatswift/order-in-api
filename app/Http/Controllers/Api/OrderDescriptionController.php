<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDescription;
use App\Http\Resources\OrderDescriptionResource;
use App\Http\Requests\StoreOrderDescriptionRequest;
use App\Http\Requests\UpdateOrderDescriptionRequest;

class OrderDescriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        $this->authorize('viewAny', OrderDescription::class);

        return OrderDescription::all();
    }

    public function show(OrderDescription $orderDescription)
    {
        $this->authorize('view', $orderDescription);

        return new OrderDescriptionResource($orderDescription);
    }

    public function store(StoreOrderDescriptionRequest $request)
    {
        $this->authorize('create', OrderDescription::class);

        $orderDescription = new OrderDescriptionResource(OrderDescription::create($request->all()));
        if ($orderDescription->save()) {
            return response()->json([
                'success' => true,
                'message' => 'OrderDescription saved successfully',
                'orderDescription' => $orderDescription
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'OrderDescription saved failed'
        ], 500);
    }

    public function update(UpdateOrderDescriptionRequest $request, OrderDescription $orderDescription)
    {
        $this->authorize('update', $orderDescription);

        $orderDescription->update($request->all());
        if ($orderDescription->save()) {
            return response()->json([
                'success' => true,
                'message' => 'OrderDescription updated successfully',
                'orderDescription' => $orderDescription
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'OrderDescription updated failed'
        ], 500);
    }

    public function updateStatus(Request $request, OrderDescription $orderDescription)
    {
        $this->authorize('update', $orderDescription);

        if ($orderDescription->order_status+1 < 4)
            $orderDescription->order_status++;

        if ($orderDescription->save()) {
            return response()->json([
                'success' => true,
                'message' => 'OrderDescription updated successfully',
                'orderDescription' => $orderDescription
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'OrderDescription updated failed'
        ], 422);
    }

    public function destroy(OrderDescription $orderDescription)
    {
        $this->authorize('delete', $orderDescription);
        
        if ($orderDescription->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'OrderDescription deleted successfully',
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'OrderDescription deleted failed'
        ], 500);
    }
}
