<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MovimientoController;

Route::post('/productos', [ProductoController::class, 'store']);
Route::get('/productos/{id}',[ProductoController::class, 'show']);
Route::post('/movimientos/entrada', [MovimientoController::class, 'storeEntrada']);
Route::post('/movimientos/entrada/sql', [MovimientoController::class, 'storeEntradaSQL']);
Route::post('/movimientos/salida', [MovimientoController::class, 'storeSalida']);
Route::post('/movimientos/salida/sql', [MovimientoController::class, 'storeSalidaSQL']);


