<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trobades', function (Blueprint $table) {
            $table->id('trob_id');
            $table->date('data');
            $table->time('hora');
            $table->string('ciutat', 100);
            $table->string('pdf_info', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trobades');
    }
};
