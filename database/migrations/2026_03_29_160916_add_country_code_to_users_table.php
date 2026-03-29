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
            $table->string('country_code', 10)->default('NP')->after('phone');
            // Make phone unique per country (composite unique constraint)
            $table->dropUnique(['phone']);
            $table->unique(['phone', 'country_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['phone', 'country_code']);
            $table->unique('phone');
            $table->dropColumn('country_code');
        });
    }
};
