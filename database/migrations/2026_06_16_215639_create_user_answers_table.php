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
    Schema::create('user_answers', function (Blueprint $table) {

        $table->id();

        $table->foreignId('quiz_session_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->foreignId('question_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->foreignId('answer_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->boolean('is_correct');

        $table->integer('points_received')->default(0);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};
