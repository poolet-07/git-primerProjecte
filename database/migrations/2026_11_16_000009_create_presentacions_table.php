<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presentacions', function (Blueprint $table) {
            $table->id('pre_id');
            $table->string('ponent', 100);
            $table->date('data');
            $table->string('ciutat', 100);
            $table->string('acta', 255);
            $table->string('ordre', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presentacions');
    }
};
