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
        Schema::create('secondary_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_booking_id')->constrained('customer_bookings')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('relation_name')->nullable();
            $table->string('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->longText('permanent_address')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->enum('same_as_permanent_address', ['yes', 'no'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secondary_details');
    }
};
