<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metges', function (Blueprint $table) {
            // Relacio 1:1: user_id es PK de metges i FK cap a users.
            $table->unsignedBigInteger('user_id')->primary();
            $table->foreign('user_id')
                ->references('id')->on('usuaris')
                ->cascadeOnDelete();

            $table->string('hospital', 255);
            $table->boolean('pertinenca');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metges');
    }
};
