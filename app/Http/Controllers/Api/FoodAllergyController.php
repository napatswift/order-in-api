<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodAllergy;
use App\Http\Resources\FoodAllergyResource;
use App\Http\Requests\StoreFoodAllergyRequest;
use App\Http\Requests\UpdateFoodAllergyRequest;

class FoodAllergyController extends Controller
{
    public function index() 
    {
        return FoodAllergy::all();
    }

    public function show(FoodAllergy $foodAllergy)
    {
        return new FoodAllergyResource($foodAllergy);
    }

    public function store(StoreFoodAllergyRequest $request)
    {
        $foodAllergy = new FoodAllergyResource(FoodAllergy::create($request->all()));
        if ($foodAllergy->save()) {
            return response()->json([
                'success' => true,
                'message' => 'FoodAllergy saved successfully',
                'foodAllergy' => $foodAllergy->food_allergies
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'FoodAllergy saved failed'
        ], 500);
    }

    public function update(UpdateFoodAllergyRequest $request, FoodAllergy $foodAllergy) 
    {
        $foodAllergy->update($request->all());
        if ($foodAllergy->save()) {
            return response()->json([
                'success' => true,
                'message' => 'FoodAllergy updated successfully',
                'foodAllergy' => $foodAllergy->food_allergies
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'FoodAllergy updated failed'
        ], 500);
    }

    public function destroy(FoodAllergy $foodAllergy)
    {
        $name = $foodAllergy->food_allergy;
        if ($foodAllergy->delete()) {
            return response()->json([
                'success' => true,
                'message' => "FoodAllergy {$name} deleted successfully"
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => "FoodAllergy {$name} deleted failed"
        ], 500);
    }
}
