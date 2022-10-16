<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order_description;
use App\Http\Resources\OrderDescriptionResource;
use App\Http\Requests\StoreOrderDescriptionRequest;
use App\Http\Requests\UpdateOrderDescriptionRequest;

class OrderDescriptionController extends Controller
{
    public function index()
    {
        return OrderDescriptionResource::all();
    }

    public function show(OrderDescription $orderDescription)
    {
        return new OrderDescriptionResource($orderDescription);
    }

    public function store(StoreOrderDescriptionRequest $request)
    {
        return new OrderDescriptionResource(OrderDescription::create($request->all()));
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

    public function destroy(OrderDescription $orderDescription)
    {
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
