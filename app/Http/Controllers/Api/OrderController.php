<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceOrderRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Food;
use App\Models\Manager;
use App\Models\OrderDescription;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        // $this->authorizeResource(Order::class, 'orders');
    }

    public function index()
    {
        $this->authorize('viewAny', Order::class);

        $user_id = Auth::id();

        if (Auth::user()->is_manager) {
            $user = Manager::find($user_id);

            if (is_null($user->restaurant)) {
                return response()->json(
                    ['message' => 'you don\'t have a restaurant'],
                    404
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

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        return new OrderResource($order);
    }

    public function store(StoreOrderRequest $request)
    {
        $this->authorize('create', Order::class);

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
        $this->authorize('update', $order);

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
        $this->authorize('delete', $order);
        
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

    public function placeNewOrder(PlaceOrderRequest $request)
    {
        $this->authorize('create', Order::class);

        $customer = Customer::findOrFail(Auth::id());
        $restaurant_id = $customer->restaurant->id;
        $new_order = new Order();
        $new_order->restaurant()->associate($restaurant_id);
        $new_order->customer()->associate($customer);

        Log::info($customer);

        if (is_null($customer->table)) {
            return response()->json([], 422);
        }

        $new_order->table()->associate($customer->table);

        if (!$new_order->save()) {
            return response()->json([
                "success" => true,
                "message" => "Order saved!",
                "order"   => new OrderResource($new_order),
            ], 422);
        }

        $order_items = $request->get('order_items');
        $order_description_array = collect([]);

        foreach ($order_items as $order_item_request) {
            $food = Food::find($order_item_request["food_id"]);
            
            $order_description = new OrderDescription();
            $order_description->order_status = 0;
            $order_description->order_quantity = $order_item_request["order_quantity"];
            if (array_key_exists('order_request', $order_item_request)){
                $order_description->order_request = $order_item_request["order_request"];
            }
            $order_description->food()->associate($order_item_request["food_id"]);
            $order_description->order_price = $food->food_price;
            $order_description->order()->associate($new_order->id);
            // $order_description->save();

            $order_description_array->push($order_description);
        }

        if (!$new_order
                ->orderDescription()
                ->saveMany($order_description_array)
            ) {
            $new_order->delete();
            return response()->json([
                "success" => true,
                "message" => "Order saved!",
                "order"   => new OrderResource($new_order),
            ], 422);
        }
        
        return response()->json([
            "success" => true,
            "message" => "Order saved!",
            "order"   => new OrderResource($new_order),
        ], 201);
    }
}
