<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::all();

        return response()->json([
            'message' => 'List of colors',
            'data' => $colors,
        ]);
    }

    public function store(Request $request)
    {
        $color = new Color();
        $color->name = $request->input('name');
        $color->code = "#COLOR_CODE#" . $request->input("code");
        $color->product_id = $request->input('product_id');
        $color->quantity = $request->input('quantity');
        $color->save();

        return response()->json([
            'message' => 'Color created successfully',
            'data' => $color,
        ]);
    }
}
