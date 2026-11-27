<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SCHEMA IF NOT EXISTS administracion');

        Schema::create('administracion.manuales', function (Blueprint $table) {
            $table->id();
            $table->string('modulo', 100);
            $table->string('titulo', 180);
            $table->text('descripcion')->nullable();
            $table->string('version', 30)->default('1.0');
            $table->string('nombre_archivo', 255)->nullable();
            $table->string('ruta_ftp', 500)->nullable();
            $table->unsignedBigInteger('tamano_bytes')->nullable();
            $table->boolean('estado')->default(true);
            $table->unsignedBigInteger('id_user_created')->nullable();
            $table->unsignedBigInteger('id_user_updated')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administracion.manuales');
    }
};
