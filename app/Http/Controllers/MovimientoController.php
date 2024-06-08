<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use Carbon\Carbon;
class MovimientoController extends Controller
{
    public function storeEntrada(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);
        $movimiento = new Movimiento($request->all());
        $movimiento->tipo = 'entrada';
        $movimiento->fecha = $request->fecha ?? Carbon::now()->toDateString(); // yyyy-mm-dd
        $movimiento->hora = $request->hora ?? Carbon::now()->toTimeString();  // hh:mm:ss

        $movimiento->save();

        $producto = Producto::findOrFail($request->producto_id);
        $producto->cantidad += $request->cantidad;
        $producto->ultimo_movimiento = now();
        $producto->update();

        return response()->json($movimiento, 201);
    }
    
    public function storeEntradaSQL(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);
    
        // Obtener la fecha y hora actuales si no se proporcionan
        $fecha = $request->fecha ?? Carbon::now()->toDateString(); // yyyy-mm-dd
        $hora = $request->hora ?? Carbon::now()->toTimeString();   // hh:mm:ss
    
        // Insertar el movimiento en la base de datos usando SQL nativo
        DB::transaction(function () use ($request, $fecha, $hora) {
            // Insertar el movimiento usando SQL sin procesar
            DB::insert('insert into movimientos (producto_id, fecha, hora, cantidad, tipo, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
                $request->producto_id,
                $fecha,
                $hora,
                $request->cantidad,
                'entrada',
                now(),
                now()
            ]);
    
            // Actualizar la cantidad del producto
            DB::update('update productos set cantidad = cantidad + ?, ultimo_movimiento = ?, updated_at = ? where id = ?', [
                $request->cantidad,
                now(),
                now(),
                $request->producto_id
            ]);
        });
    
        // Obtener el movimiento recién insertado para devolverlo en la respuesta
        $movimiento = DB::table('movimientos')
            ->where('producto_id', $request->producto_id)
            ->where('fecha', $fecha)
            ->where('hora', $hora)
            ->first();
    
        // Devolver la respuesta JSON con el movimiento creado
        return response()->json($movimiento, 201);
    }

    public function storeSalida(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);
        $movimiento = new Movimiento($request->all());
        $movimiento->tipo = 'salida';
        $movimiento->fecha = $request->fecha ?? Carbon::now()->toDateString(); // yyyy-mm-dd
        $movimiento->hora = $request->hora ?? Carbon::now()->toTimeString();  // hh:mm:ss

        $movimiento->save();

        $producto = Producto::findOrFail($request->producto_id);
        $producto->cantidad -= $request->cantidad;
        $producto->ultimo_movimiento = now();
        $producto->update();

        return response()->json($movimiento, 201);
    }

    public function storeSalidaSQL(Request $request)
    {
        // Validar los datos que vienen en la solicitud
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        // Obtener la fecha y hora actuales si no se proporcionan
        $fecha = $request->fecha ?? Carbon::now()->toDateString();
        $hora = $request->hora ?? Carbon::now()->toTimeString();

        
            // Iniciar la transacción
        DB::transaction(function () use ($request, $fecha, $hora) {
            // Insertar el movimiento de salida usando SQL sin procesar
            DB::insert('insert into movimientos (producto_id, fecha, hora, cantidad, tipo, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
                $request->producto_id,
                $fecha,
                $hora,
                $request->cantidad,
                'salida',
                now(),
                now()
            ]);

            // Actualizar la cantidad del producto restando la cantidad de salida
            DB::update('update productos set cantidad = cantidad - ?, ultimo_movimiento = ?, updated_at = ? where id = ?', [
                $request->cantidad,
                now(),
                now(),
                $request->producto_id
            ]);
        });

        // Obtener el movimiento recién insertado para devolverlo en la respuesta
        $movimiento = DB::table('movimientos')
            ->where('producto_id', $request->producto_id)
            ->where('fecha', $fecha)
            ->where('hora', $hora)
            ->first();

        // Devolver la respuesta JSON con el movimiento creado
        return response()->json($movimiento, 201);
    
    }
}
