<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePromotionRequest;
use App\Http\Requests\UpdatePromotionRequest;
use App\Http\Resources\PromotionResource;
use App\Models\Manager;
use App\Models\Promotion;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        // $this->authorizeResource(Food::class, 'food');
    }

    public function index()
    {
        $this->authorize('viewAny', Promotion::class);

        return PromotionResource::collection(Promotion::all());
    }

    public function show(Promotion $promotion)
    {
        $this->authorize('view', $promotion);

        return new PromotionResource($promotion);
    }

    public function store(StorePromotionRequest $request)
    {
        $this->authorize('create', Promotion::class);
        // only manager can do this
        $manager = Manager::findOrFail(Auth::id());

        $promotion = Promotion::create(
            array_merge($request->all(),
            ['restaurant_id' => $manager->restaurant->id])
        );

        // $promotion->restaurant_id = Auth::user()->restaurant->id;

        $im_extension = $request->file('image')->extension();
        $promotion
            ->addMediaFromRequest('image')
            ->usingFileName(fake()->uuid().'.'.$im_extension)
            ->toMediaCollection();
        
        if ($promotion->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Promotion saved successfully',
                'promotion' => $promotion->id
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Promotion saved failed'
        ], 500);
    }

    public function update(UpdatePromotionRequest $request, Promotion $promotion)
    {
        $this->authorize('update', $promotion);

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
        $this->authorize('delete', $promotion);
        
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
