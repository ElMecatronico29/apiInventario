<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['nombre', 'cantidad', 'ultimo_movimiento'];
    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }
}
