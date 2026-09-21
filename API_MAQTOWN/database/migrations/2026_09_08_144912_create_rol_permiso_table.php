<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rol_permiso', function (Blueprint $table) {
            $table->id();
            $table->integer('idRoles');
            $table->unsignedBigInteger('idPermisos');
            $table->boolean('activo')->default(true);
            $table->dateTime('fecha_registro')->useCurrent();

            $table->foreign('idRoles')->references('idRoles')->on('roles')->onDelete('cascade');
            $table->foreign('idPermisos')->references('idPermisos')->on('permisos')->onDelete('cascade');

            $table->unique(['idRoles', 'idPermisos']); // evita duplicar la misma asignación
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rol_permiso');
    }
};