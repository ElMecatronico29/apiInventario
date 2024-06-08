<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $fillable = ['producto_id', 'fecha', 'hora', 'cantidad', 'tipo'];
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
