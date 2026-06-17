<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_session_questions', function (Blueprint $table) {
            $table->json('answer_order')->nullable()->after('display_order');
            $table->boolean('is_answered')->default(false)->after('answer_order');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_session_questions', function (Blueprint $table) {
            $table->dropColumn(['answer_order', 'is_answered']);
        });
    }
};