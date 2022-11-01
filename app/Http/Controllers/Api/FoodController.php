<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFoodRequest;
use App\Http\Requests\UpdateFoodRequest;
use App\Http\Resources\FoodResource;
use App\Models\Food;
use App\Models\Manager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FoodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        // $this->authorizeResource(Food::class, 'food');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Food::class);
        
        // $param_fields = $request->query('fields', '');
        // $included_fileds = explode(',', $param_fields);
        // $columns = collect(['food_name', 'food_price', 'food_detail', 'cooking_time']);
        // $included_fileds = $columns->intersect($included_fileds)->toArray();
        
        $param_relations = $request->query('relations', '');
        $included_relations = explode(',', $param_relations);
        $relations = collect(['foodAllergies', 'restaurant', 'promotion', 'categories']);
        $included_relations = $relations->intersect($included_relations)->toArray();

        $food_list = Food::with($included_relations)->get();

        // Log::info(Food::
        //     // select($included_fileds)->
        //     with($included_relations)->get());

        return FoodResource::collection(
            $food_list
        );
    }

    public function show(Food $food)
    {
        $this->authorize('view', $food);

        return new FoodResource($food);
    }

    public function store(StoreFoodRequest $request)
    {

        $this->authorize('create', Food::class);

        $user_id = Auth::id();
        if (is_null($user_id)) {
            return response('user id is null', 500);
        }

        $manager = Manager::find($user_id);

        if (is_null($manager->restaurant)) {
            return response('You dont have restaurant', 400);
        }

        $food = Food::create(
            array_merge(
                $request->all(),
                ['restaurant_id' => $manager->restaurant->id])
        );
        
        $food->categories()->attach($request->get('category_ids'));
        $food->foodAllergies()->attach($request->get('food_allergy_ids'));

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
        $this->authorize('update', $food);

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
        $this->authorize('delete', $food);

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
