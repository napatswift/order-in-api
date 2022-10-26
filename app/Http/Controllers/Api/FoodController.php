<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFoodRequest;
use App\Http\Requests\UpdateFoodRequest;
use App\Http\Resources\FoodResource;
use App\Models\Food;

class FoodController extends Controller
{
    public function index()
    {
        return Food::all();
    }

    public function show(Food $food)
    {
        return new FoodResource($food);
    }

    public function store(StoreFoodRequest $request)
    {
        $food = new FoodResource(Food::create($request->all()));
        if ($food->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Food saved successfully',
                'food_id' => $food->id
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Food saved failed'
        ], 500);
    }

    public function update(UpdateFoodRequest $request, Food $food)
    {
        $food->update($request->all());
        if ($food->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Food updated successfully',
                'food_id' => $food->id
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Food updated failed'
        ], 500);
    }

    public function destroy(Food $food)
    {
        $name = $food->food_name;
        if ($food->delete()) {
            return response()->json([
                'success' => "Food {$name} deleted successfully"
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => "Food {$name} deleted failed"
        ], 500);
    }
}
