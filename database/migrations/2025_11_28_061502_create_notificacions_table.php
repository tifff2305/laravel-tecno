<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas')->onDelete('cascade');
            $table->enum('tipo', ['recordatorio', 'confirmacion']);
            $table->enum('canal', ['email', 'whatsapp']);
            $table->enum('estado', ['pendiente', 'enviado'])->default('pendiente');
            $table->timestamp('fecha_envio')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};