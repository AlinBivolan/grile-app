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
    Schema::create('quiz_session_questions', function (Blueprint $table) {

        $table->id();

        $table->foreignId('quiz_session_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->foreignId('question_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->integer('display_order');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_session_questions');
    }
};
