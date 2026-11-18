<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacients', function (Blueprint $table) {
            $table->id('pac_id');

            // Cada pacient pertany a una familia.
            $table->unsignedBigInteger('fam_id');
            $table->foreign('fam_id')
                ->references('user_id')->on('families')
                ->cascadeOnDelete();

            $table->string('nom', 100);
            $table->date('data_naix');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacients');
    }
};
