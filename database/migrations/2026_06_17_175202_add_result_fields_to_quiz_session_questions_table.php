<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_session_questions', function (Blueprint $table) {
            $table->json('selected_answer_ids')
                ->nullable()
                ->after('answer_order');

            $table->boolean('is_correct')
                ->nullable()
                ->after('is_answered');

            $table->timestamp('answered_at')
                ->nullable()
                ->after('is_correct');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_session_questions', function (Blueprint $table) {
            $table->dropColumn([
                'selected_answer_ids',
                'is_correct',
                'answered_at',
            ]);
        });
    }
};