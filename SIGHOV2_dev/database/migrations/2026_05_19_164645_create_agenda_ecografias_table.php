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
        Schema::create('plataforma.agenda_ecografia', function (Blueprint $table) {

            $table->id();
            $table->date('fecha_agenda');
            $table->time('hora_agenda');
            $table->boolean('confirmado')->nullable();
            $table->boolean('asistencia')->nullable();
            $table->boolean('anulacion_ficha')
                ->default(false);
            $table->timestamp('fecha_anulacion')
                ->nullable();
            $table->string('motivo_anulacion')
                ->nullable();
            $table->string('observacion')
                ->nullable();
            $table->string('clave_unica')
                ->unique();
            $table->string('ip', 100)
                ->nullable();
            $table->string('nombre_equipo')
                ->nullable();
            $table->unsignedBigInteger('id_persona')
                ->nullable();
            $table->unsignedBigInteger('id_servicio_plataforma')
                ->nullable();
            $table->unsignedBigInteger('id_asignacion_horario');
            $table->unsignedBigInteger('id_user_created')
                ->nullable();
            $table->unsignedBigInteger('id_user_updated')
                ->nullable();
            $table->unsignedBigInteger('id_usuario_anula')
                ->nullable();
            $table->timestamps();

            $table->foreign('id_persona')
                ->references('id')
                ->on('administracion.persona');

            $table->foreign('id_servicio_plataforma')
                ->references('id')
                ->on('plataforma.servicios_plataforma');

            $table->foreign('id_asignacion_horario')
                ->references('id')
                ->on('plataforma.asignacion_horario');
        });
    }

    /**
     * Reverse the migrations.
     */
   
    public function down(): void
    {
        Schema::dropIfExists('plataforma.agenda_ecografia');
    }
};
