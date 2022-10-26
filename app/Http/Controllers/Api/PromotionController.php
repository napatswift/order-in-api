<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePromotionRequest;
use App\Http\Requests\UpdatePromotionRequest;
use App\Http\Resources\PromotionResource;
use App\Models\Promotion;

class PromotionController extends Controller
{
    public function index()
    {
        return Promotion::all();
    }

    public function show(Promotion $promotion)
    {
        return new PromotionResource($promotion);
    }

    public function store(StorePromotionRequest $request)
    {
        return new PromotionResource(Promotion::create($request->all()));
        if ($promotion->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Promotion saved successfully',
                'promotion' => $promotion
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Promotion saved failed'
        ], 500);
    }

    public function update(UpdatePromotionRequest $request, Promotion $promotion)
    {
        $promotion->update($request->all());
        if ($promotion->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Promotion updated successfully',
                'promotion' => $promotion
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Promotion updated failed'
        ], 500);
    }

    public function destroy(Promotion $promotion)
    {
        if ($promotion->delete()) {
            return response()->json([
                'success' => "Promotion deleted successfully"
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Promotion deleted failed'
        ], 500);
    }
}
