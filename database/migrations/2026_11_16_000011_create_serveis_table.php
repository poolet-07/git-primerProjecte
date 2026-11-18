<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('serveis', function (Blueprint $table) {
            $table->id('servei_id');
            $table->string('tipus', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('serveis');
    }
};
