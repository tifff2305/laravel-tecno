<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_clinico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->unique()->constrained('pacientes')->onDelete('cascade');
            $table->text('antecedentes_medicos')->nullable();
            $table->text('alergias')->nullable();
            $table->text('observaciones')->nullable();
            $table->date('fecha_apertura');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_clinico');
    }
};