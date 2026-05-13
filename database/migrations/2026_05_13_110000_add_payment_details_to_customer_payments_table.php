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
        Schema::table('customer_payments', function (Blueprint $table) {
            $table->enum('payment_status', ['booked', 'hold', 'emi'])->default('hold')->after('transaction_number');
            $table->string('receipt_number')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_payments', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'receipt_number']);
        });
    }
};
