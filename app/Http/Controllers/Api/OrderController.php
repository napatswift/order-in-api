<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Manager;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Food::class, 'food');
    }

    public function index(Request $request)
    {
        $order_of_user = $request->query('ofuser', false);

        if ($order_of_user) {
            $user_id = Auth::id();

            if (Auth::user()->is_manager) {
                $user = Manager::find($user_id);

                if (is_null($user->restaurant)) {
                    return response()->json(
                        ['message' => 'you don\'t have a restaurant'], 404
                    );
                }
                return OrderResource::collection(
                    Order::where('restaurant_id', $user->restaurant->id)
                    ->with(['orderDescription'])
                    ->get()
                );
            }

            if (Auth::user()->is_employee) {
                $user = Employee::find($user_id);

                return OrderResource::collection(
                    Order::where('restaurant_id', $user->restaurant->id)
                    ->with(['orderDescription'])
                    ->get()
                );
            }

            return OrderResource::collection(
                Order::where('customer_id', $user_id)
                    ->with(['orderDescription'])
                    ->get()
            );
        }

        // return OrderResource::collection(
        //     Order::with(['orderDescription'])
        //         ->get()
        // );
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    public function store(StoreOrderRequest $request)
    {
        $order = new OrderResource(Order::create($request->all()));
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
