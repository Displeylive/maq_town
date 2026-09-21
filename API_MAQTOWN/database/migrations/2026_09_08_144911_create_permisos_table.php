<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permisos', function (Blueprint $table) {
            $table->id('idPermisos');
            $table->string('nombre', 100)->unique();      // ej: crear_producto
            $table->string('descripcion', 255)->nullable(); // texto legible para el CRUD
            $table->string('modulo', 100);                 // ej: productos, ventas
            $table->boolean('activo')->default(true);
            $table->dateTime('fecha_registro')->useCurrent();
            $table->dateTime('fecha_mod')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permisos');
    }
};