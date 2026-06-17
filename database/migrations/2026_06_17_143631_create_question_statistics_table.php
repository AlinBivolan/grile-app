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
    Schema::create('question_statistics', function (Blueprint $table) {

        $table->id();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('question_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->integer('times_seen')->default(0);

        $table->integer('times_correct')->default(0);

        $table->integer('times_wrong')->default(0);

        $table->integer('current_streak')->default(0);

        $table->timestamp('last_seen_at')->nullable();

        $table->timestamps();

        $table->unique(['user_id', 'question_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_statistics');
    }
};
