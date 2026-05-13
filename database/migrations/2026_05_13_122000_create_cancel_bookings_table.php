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
        if (! Schema::hasTable('cancel_bookings')) {
            Schema::create('cancel_bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_booking_id')->constrained('customer_bookings')->cascadeOnDelete();
                $table->foreignId('plot_sale_detail_id')->nullable()->constrained('plot_sale_details')->nullOnDelete();
                $table->decimal('deduction_amount', 16, 2)->nullable();
                $table->decimal('deduction_percentage', 8, 2)->nullable();
                $table->decimal('refund_amount', 16, 2)->nullable();
                $table->enum('pay_mode', ['cash', 'cheque', 'dd', 'neft_rtgs', 'card'])->nullable();
                $table->date('pay_date')->nullable();
                $table->string('bank_name')->nullable();
                $table->string('account_number')->nullable();
                $table->string('ifsc_code')->nullable();
                $table->date('cheque_date')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cancel_bookings');
    }
};
