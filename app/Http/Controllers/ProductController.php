<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    // Get all products
    public function index()
    {
        $products = Product::all();
        $products = $this->addCreatedFromColumn($products);
        return response()->json($products);
    }

    // Get a specific product
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $product = $this->addCreatedFromColumn([$product])->first();
        return response()->json($product);
    }

    // Create a new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::create($request->all());

        return response()->json($product, 201);
    }

    // Update a product
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product->update($request->all());

        return response()->json($product, 200);
    }

    // Delete a product
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(null, 204);
    }

    private function addCreatedFromColumn($products)
{
    $collection = collect($products);

    return $collection->map(function ($product) {
        $product->created_from = $this->calculateCreatedAt($product->created_at);
        return $product;
    });
}
private function calculateCreatedAt($created_at)
{
    $diff = now()->diffInHours($created_at);
    $created_from = $diff == 1 ? $diff . ' hour ago' : $diff . ' hours ago';

    return $created_from;
}
}
