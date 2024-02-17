<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    // Get all categories
    public function index()
    {
        $categories = Category::all();

        // Add a dynamically created 'created_from' column based on 'created_at'
        $categories = $this->addCreatedFromColumn($categories);

        return response()->json($categories);
    }

    // Get a specific category
    public function show($id)
    {
        $category = Category::findOrFail($id);

        // Add a dynamically created 'created_from' column based on 'created_at'
        $category = $this->addCreatedFromColumn([$category])->first();

        return response()->json($category);

    }

    // Create a new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories',
        ]);

        $category = Category::create($request->all());

        return response()->json($category, 201);
    }

    // Update a category
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $id,
        ]);

        $category->update($request->all());

        return response()->json($category, 200);
    }

    // Delete a category
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(null, 204);
    }

    private function addCreatedFromColumn($categories)
{
    $collection = collect($categories);

    return $collection->map(function ($category) {
        $category->created_from = $this->calculateCreatedAt($category->created_at);
        return $category;
    });
}
private function calculateCreatedAt($created_at)
{
    $diff = now()->diffInHours($created_at);
    $created_from = $diff == 1 ? $diff . ' hour ago' : $diff . ' hours ago';

    return $created_from;
}


}
