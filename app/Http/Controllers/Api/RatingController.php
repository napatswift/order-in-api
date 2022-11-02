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
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $this->authorize('viewAny', Rating::class);

        return Rating::all();
    } 

    public function show(Rating $rating)
    {
        $this->authorize('view', $rating);

        return new RatingResource($rating);
    }

    public function store(StoreRatingRequest $request)
    {
        $this->authorize('create', Rating::class);

        $rating = new RatingResource(Rating::create($request->all()));
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
        $this->authorize('update', $rating);

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
        $this->authorize('delete', $rating);
        
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
