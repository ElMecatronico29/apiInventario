<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre'=>'required',
            'cantidad' => 'required|integer|min:1',
        ]);
        $producto = Producto::create($request->all());
        return response()->json($producto, 201);
    }

    public function show($id)
    {
        $producto = Producto::with('movimientos')->findOrFail($id);
        return response()->json($producto);
    }
}
