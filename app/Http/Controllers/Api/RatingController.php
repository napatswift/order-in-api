<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use App\Http\Resources\RatingResource;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::all();
        return RatingResource::collection($ratings);
    } 

    public function show(Rating $rating)
    {
        return new RatingResource($rating);
    }

    public function store(StoreRatingRequest $request)
    {
        return new RatingResource(Rating::create($request->all()));
        if ($rating->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Rating saved successfully',
                'rating' => $rating
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Rating saved failed'
        ], 500);
    }

    public function update(UpdateRatingRequest $request, Rating $rating)
    {
        $rating->update($request->all());
        if ($rating->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Rating updated successfully',
                'rating' => $rating
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Rating updated failed'
        ], 500);
    }

    public function destroy(Rating $rating)
    {
        if ($rating->delete()) {
            return response()->json([
                'success' => "Rating deleted successfully"
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Rating deleted failed'
        ], 500);
    }
}
