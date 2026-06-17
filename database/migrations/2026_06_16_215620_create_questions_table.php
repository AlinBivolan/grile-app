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
    Schema::create('questions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('category_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->string('image_path');

        $table->text('explanation')->nullable();

        // single = o variantă corectă
        // multiple = mai multe variante corecte
        $table->enum('answer_type', ['single', 'multiple'])
              ->default('single');

        $table->integer('difficulty')->default(1);

        $table->boolean('is_active')->default(true);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
