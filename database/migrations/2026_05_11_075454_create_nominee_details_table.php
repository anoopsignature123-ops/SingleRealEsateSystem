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
        Schema::create('nominee_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_booking_id')->constrained('customer_bookings')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('ralation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nominee_details');
    }
};
