<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('families', function (Blueprint $table) {
            // Relacio 1:1: user_id es PK de families i FK cap a users.
            $table->unsignedBigInteger('user_id')->primary();
            $table->foreign('user_id')
                ->references('id')->on('usuaris')
                ->cascadeOnDelete();

            $table->string('foto_csns', 255);
            $table->string('what_csns', 50);
            $table->string('dades_csns', 255);
            $table->string('direccio', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
