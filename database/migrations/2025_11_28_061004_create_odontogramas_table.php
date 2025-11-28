<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('odontograma', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->tinyInteger('pieza_dental');
            $table->enum('estado', ['sano', 'caries', 'obturacion', 'corona', 'ausente', 'otro'])->default('sano');
            $table->text('observaciones')->nullable();
            $table->date('fecha_registro');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odontograma');
    }
};