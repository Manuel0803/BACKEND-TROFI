<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade'); // quien hace la reseña
        $table->foreignId('reviewed_id')->constrained('users')->onDelete('cascade'); // quien recibe la reseña
        $table->text('description');
        $table->unsignedTinyInteger('score'); // 1 a 5, por ejemplo
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
