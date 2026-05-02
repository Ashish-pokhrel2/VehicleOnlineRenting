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
        Schema::create('booking_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();

            $table->string('purchase_order_id')->unique();
            $table->string('purchase_order_name');
            $table->string('pidx')->nullable()->unique();
            $table->string('transaction_id')->nullable()->index();

            $table->decimal('amount', 12, 2);
            $table->decimal('service_fee', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2);

            $table->string('payment_url')->nullable();
            $table->string('return_url')->nullable();
            $table->string('website_url')->nullable();

            $table->enum('status', ['Initiated', 'Pending', 'Completed', 'Failed', 'Expired', 'User canceled', 'Refunded'])
                ->default('Initiated');

            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->json('lookup_payload')->nullable();

            $table->timestamp('initiated_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            $table->timestamps();

            $table->index(['booking_id', 'status']);
            $table->index(['customer_id', 'vendor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_payments');
    }
};
