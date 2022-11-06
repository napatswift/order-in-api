<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Customer;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

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

        $user_id = Auth::id();
        $customer = Customer::findOrFail($user_id);

        $review = Review::create(
            array_merge(
                $request->all(),
                [
                    'restaurant_id' => $customer->restaurant_id
                ]
            )
        );

        $new_rating_list = collect([]);

        if ($review->save()) {
            foreach ($request->ratings as $rating) {
                $new_rating = new Rating($rating);
                $new_rating_list->push($new_rating);
            }

            if ($review->rating()->saveMany($new_rating_list)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Review saved successfully',
                    'review'  => $review
                ], 201);
            }

            $review->delete();
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
                'review'  => $review
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
