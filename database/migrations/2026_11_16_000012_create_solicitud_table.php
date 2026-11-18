<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitud', function (Blueprint $table) {
            $table->id('sol_id');

            // La sol·licitud pertany a una familia.
            $table->unsignedBigInteger('fam_id');
            $table->foreign('fam_id')
                ->references('user_id')->on('families')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('servei_id');
            $table->foreign('servei_id')
                ->references('servei_id')->on('serveis')
                ->restrictOnDelete();

            $table->string('estat', 25);
            $table->text('observacions');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitud');
    }
};
