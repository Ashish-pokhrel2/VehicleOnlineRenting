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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('pickup_time')->nullable()->after('end_date');
            $table->string('full_name')->nullable()->after('pickup_time');
            $table->string('phone')->nullable()->after('full_name');
            $table->string('email')->nullable()->after('phone');
            $table->string('citizenship_id')->nullable()->after('email');
            $table->text('special_request')->nullable()->after('citizenship_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'pickup_time',
                'full_name',
                'phone',
                'email',
                'citizenship_id',
                'special_request',
            ]);
        });
    }
};
