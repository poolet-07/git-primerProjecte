<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publicacions', function (Blueprint $table) {
            $table->id('pub_id');
            $table->string('autor', 100);
            // 'any' evita utilitzar una lletra accentuada com a nom de columna.
            $table->year('any');
            $table->string('url', 2048);
            $table->string('revista', 150);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publicacions');
    }
};
