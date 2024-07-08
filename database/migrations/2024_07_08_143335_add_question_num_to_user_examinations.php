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
        Schema::table('user_examinations', function (Blueprint $table) {
            $table->integer('question_num')->default(0)->after('challenge_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_examinations', function (Blueprint $table) {
            $table->dropColumn('question_num');
        });
    }
};
