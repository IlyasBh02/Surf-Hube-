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
        Schema::table('users', function (Blueprint $table) {
            // Add fields for coach profiles
            $table->text('bio')->nullable()->after('coach_approved');
            $table->text('description')->nullable()->after('bio');
            $table->integer('years_experience')->nullable()->after('description');
            $table->string('profile_picture')->nullable()->after('years_experience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio', 'description', 'years_experience', 'profile_picture']);
        });
    }
};
