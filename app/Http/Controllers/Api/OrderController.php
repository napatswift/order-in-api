<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();

        return OrderResource::collection($orders);
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    public function store(StoreOrderRequest $request)
    {
        return new OrderResource(Order::create($request->all()));
        if ($order->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Order saved successfully',
                'order' => $order
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Order saved failed'
        ], 500);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->all());
        if ($order->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'order' => $order
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Order updated failed'
        ], 500);
    }

    public function destroy(Order $order)
    {
        if ($order->delete()) {
            return response()->json([
                'success' => "Order deleted successfully"
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Order deleted failed'
        ], 500);
    }
}
