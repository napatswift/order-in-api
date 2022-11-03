<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Http\Requests\UpdateReviewRequest;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        $this->authorize('viewAny', Review::class);

        return Review::all();
    }

    public function show(Review $review)
    {
        $this->authorize('view', $review);

        return new ReviewResource($review);
    }

    public function store(StoreReviewRequest $request)
    {
        $this->authorize('create', Review::class);

        $review = new ReviewResource(Review::create($request->all()));
        if ($review->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Review saved successfully',
                'review' => $review
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Review saved failed'
        ], 500);
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        $this->authorize('update', $review);

        $review->update($request->all());
        if ($review->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'review' => $review
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Review updated failed'
        ], 500);
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        
        if ($review->delete()) {
            return response()->json([
                'success' => "Review deleted successfully"
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Review deleted failed'
        ], 500);
    }
}
