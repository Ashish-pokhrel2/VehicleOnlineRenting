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
        Schema::create('booking_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('booking_payment_id')->constrained('booking_payments')->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();

            $table->decimal('gross_amount', 12, 2);
            $table->decimal('service_fee', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2);

            $table->enum('status', ['Held', 'Released'])->default('Held');
            $table->timestamp('settled_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique('booking_payment_id');
            $table->index(['booking_id', 'vendor_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_settlements');
    }
};
