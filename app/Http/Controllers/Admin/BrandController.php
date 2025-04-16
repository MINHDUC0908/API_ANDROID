<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Exception;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return response()->json([
            "message" => "List of brands",
            "data" => $brands
        ]);
    }

    public function store(Request $request)
    {
        try {
            $brand = new Brand();
            $brand->brand_name = $request->input("brand_name");
            $brand->category_id = $request->input("category_id");
            if ($request->hasFile("logo_brand")) {
                $file = $request->file("logo_brand");
                $filename = time() . "_" . $file->getClientOriginalName();
                $file->move(public_path("brands"), $filename);
                $brand->logo_brand = "brands/" . $filename;
            }

            $brand->save();

            return response()->json([
                "message" => "Brand created successfully",
                "data" => $brand
            ]);
        } catch (Exception $e)
        {
            return response()->json([
                "message" => "Error creating brand",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
