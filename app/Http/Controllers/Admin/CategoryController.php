<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            "message" => "List of categories",
            "data" => $categories
        ]);
    }
    public function store(Request $request)
    {
        $category = new Category();
        $category->category_name = $request->input("category_name");
        $category->save();
        return response()->json([
            "message" => "Category created successfully",
            "data" => $category
        ]);
    }
}
