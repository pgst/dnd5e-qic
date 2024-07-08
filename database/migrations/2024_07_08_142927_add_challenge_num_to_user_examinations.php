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
            $table->unsignedInteger('challenge_num')->default(0)->after('cleared');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_examinations', function (Blueprint $table) {
            $table->dropColumn('challenge_num');
        });
    }
};
