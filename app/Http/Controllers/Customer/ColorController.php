<?php

namespace App\Http\Controllers\Customer;

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
}
