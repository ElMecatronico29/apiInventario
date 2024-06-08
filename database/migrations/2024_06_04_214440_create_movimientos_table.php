<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained()->onDelete('cascade');
            $table->date('fecha');
            $table->time('hora'); 
            $table->integer('cantidad');
            $table->enum('tipo',['entrada','salida']);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
