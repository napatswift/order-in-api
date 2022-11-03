<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Manager;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Category::class);

        return CategoryResource::collection(Category::get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $this->authorize('create', Category::class);

        $manager = Manager::findOrFail(Auth::id());

        $category = Category::create(
            array_merge($request->all(),[
                'restaurant_id' => $manager->restaurant->id
            ])
        );
        $category = new CategoryResource($category);

        $im_extension = $request->file('image')->extension();
        $category
            ->addMediaFromRequest('image')
            ->usingFileName(fake()->uuid().'.'.$im_extension)
            ->toMediaCollection();

        if ($category->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Category saved successfully',
                'category' => $category->id
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Category saved failed'
        ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);

        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->authorize('update', $category);

        $category->update($request->all());
        if ($category->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'category' => $category
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Category updated failed'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $name = $category->name;
        if ($category->delete()) {
            return response()->json([
                'success' => "Category {$name} deleted successfully"
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => "Category {$name} deleted failed"
        ], 500);
    }
}
