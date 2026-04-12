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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['Car', 'Bike', 'Scooter', 'Van']);
            $table->string('category');

            $table->decimal('price_per_day', 10, 2);
            $table->float('rating')->default(0);
            $table->integer('reviews')->default(0);
            $table->string('image')->nullable();
            $table->json('features')->nullable();
            $table->text('description');
            $table->integer('seats')->nullable();
            $table->string('transmission')->nullable();
            $table->string('fuel')->nullable();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('available')->default(true);
            $table->timestamps();
            $table->index('vendor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
