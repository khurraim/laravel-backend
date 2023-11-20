<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Create a new category
    public function create(Request $request)
    {
        $category = new Category;
        $category->name = $request->name;
        $category->description = $request->description;
        // Set other attributes as needed
        $category->save();

        return response()->json(['message' => 'Category created successfully'], 201);
    }

    // Retrieve all categories
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories, 200);
    }

    // Retrieve a specific category by ID
    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category, 200);
    }

    // Update a category by ID
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->name = $request->name;
        $category->description = $request->description;
        // Update other attributes as needed
        $category->save();

        return response()->json(['message' => 'Category updated successfully'], 200);
    }

    // Delete a category by ID
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
