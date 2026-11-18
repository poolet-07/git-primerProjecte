<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fotos_pre', function (Blueprint $table) {
            $table->id('foto_id');
            $table->foreignId('pre_id')
                ->constrained('presentacions', 'pre_id')
                ->cascadeOnDelete();
            $table->string('ruta', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fotos_pre');
    }
};
