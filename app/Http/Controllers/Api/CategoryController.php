<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        $category = new CategoryResource(Category::create($request->all()));

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
        $category->update($request->all());
        if ($category->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'category' => $category
            ], 200);
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
