<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'prenom')) {
                $table->string('prenom')->nullable();
            }
            if (!Schema::hasColumn('users', 'sexe')) {
                $table->string('sexe')->nullable();
            }
            if (!Schema::hasColumn('users', 'poids')) {
                $table->integer('poids')->nullable();
            }
            if (!Schema::hasColumn('users', 'hauteur')) {
                $table->integer('hauteur')->nullable();
            }
            if (!Schema::hasColumn('users', 'experience')) {
                $table->boolean('experience')->default(false);
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('surfer');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['prenom', 'sexe', 'poids', 'hauteur', 'experience', 'role']);
        });
    }
}; 