<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColorController extends Controller
{
    public function index()
    {
        $name = Auth::user()->name;
        $colors = Color::orderBy("id", "DESC")->paginate(15);
        return view('admin.colors.list', compact('name', 'colors'));
    }
    public function create()
    {
        $products = Product::orderBy('id', 'DESC')->get();
        return view('admin.colors.create', compact('products'));
    }
    public function store(Request $request)
    {
        $color = new Color();
        $color->name = $request->input('name') ?? "None";
        $color->code = $request->input('code') ?? "None";
        $color->product_id = $request->input('product_id');
        $color->quantity = $request->input('quantity');
        $color->save();
        return redirect()->route('colors.index')->with('status', "New color added successfully!");
    }
    public function show($id)
    {
        $color = Color::findOrFail($id);
        return view('admin.colors.show', compact('color'));
    }
    public function edit($id)
    {
        $color = Color::findOrFail($id);
        $products = Product::orderBy('id', 'DESC')->get();
        return view('admin.colors.edit', compact('color', 'products'));
    }
    public function update(Request $request, $id)
    {
        $color = Color::findOrFail($id);
        $color->color = $request->input('color') ?? "None";
        $color->product_id = $request->input('product_id');
        $color->quantity = $request->input('quantity');
        $color->save();
        return redirect()->route('colors.index')->with('status', 'Color updated successfully!');
    }
    public function destroy($id)
    {
        $color = Color::findOrFail($id);
        $color->delete();
        return redirect()->route('colors.index')->with('status', 'Color deleted successfully!');
    }    
}
