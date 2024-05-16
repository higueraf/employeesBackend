<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('cedula');
            $table->unsignedBigInteger('id_provincia');
            $table->foreign('id_provincia')->references('id')->on('provincias');
            $table->date('fecha_nacimiento');
            $table->string('email');
            $table->text('observaciones');
            $table->text('foto');
            $table->date('fecha_ingreso');
            $table->string('cargo');
            $table->string('codigo');
            $table->string('departamento');
            $table->boolean('jornada_parcial')->default(false);
            $table->boolean('estado')->default(false);
            $table->unsignedBigInteger('id_provincia_cargo');
            $table->text('observaciones_cargo');
            $table->foreign('id_provincia_cargo')->references('id')->on('provincias');
            $table->decimal('sueldo', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
