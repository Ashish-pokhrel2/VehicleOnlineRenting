<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                if (! Schema::hasColumn('reviews', 'booking_id')) {
                    $table->foreignId('booking_id')
                        ->nullable()
                        ->after('id')
                        ->constrained('bookings')
                        ->nullOnDelete();
                }

                if (! Schema::hasColumn('reviews', 'vendor_reply')) {
                    $table->text('vendor_reply')->nullable()->after('comment');
                }
            });

            return;
        }

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();

            $table->unsignedTinyInteger('rating');
            $table->text('comment');
            $table->text('vendor_reply')->nullable();

            $table->timestamps();

            $table->index(['vendor_id', 'rating']);
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('reviews')) {
            return;
        }

        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'booking_id')) {
                $table->dropConstrainedForeignId('booking_id');
            }

            if (Schema::hasColumn('reviews', 'vendor_reply')) {
                $table->dropColumn('vendor_reply');
            }
        });
    }
};
