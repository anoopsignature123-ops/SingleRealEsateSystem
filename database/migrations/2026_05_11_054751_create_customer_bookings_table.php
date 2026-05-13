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
        Schema::create('customer_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('associate_id')->nullable()->constrained('associates')->nullOnDelete();
            $table->enum('customer_type', ['returning_customer', 'sale_customer', 'sale_to_associate'])->nullable();
            $table->string('customer_id')->nullable();
            $table->string('customer_code')->nullable();
            $table->string('booking_code')->nullable()->unique();
            $table->string('customer_name')->nullable();
            $table->string('associate_code')->nullable();
            $table->string('associate_name')->nullable();
            $table->integer('current_step')->default(1);
            $table->enum('status', ['pending', 'completed', 'draft', 'cancelled'])->default('draft');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_bookings');
    }
};
