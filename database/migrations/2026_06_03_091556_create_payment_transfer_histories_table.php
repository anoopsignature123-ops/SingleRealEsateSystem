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
        Schema::create('payment_transfer_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_payment_id')->constrained('customer_payments')->cascadeOnDelete();
            $table->foreignId('old_customer_booking_id')->constrained('customer_bookings')->cascadeOnDelete();
            $table->foreignId('new_customer_booking_id')->constrained('customer_bookings')->cascadeOnDelete();
            $table->foreignId('old_plot_sale_detail_id')->constrained('plot_sale_details')->cascadeOnDelete();
            $table->foreignId('new_plot_sale_detail_id')->constrained('plot_sale_details')->cascadeOnDelete();
            $table->string('old_booking_code')->nullable();
            $table->string('new_booking_code')->nullable();
            $table->string('old_customer_code')->nullable();
            $table->string('new_customer_code')->nullable();
            $table->string('old_customer_name')->nullable();
            $table->string('new_customer_name')->nullable();
            $table->decimal('transfer_amount', 12, 2)->default(0);
            $table->date('transfer_date');
            $table->text('transfer_reason')->nullable();
            $table->text('remark')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transfer_histories');
    }
};