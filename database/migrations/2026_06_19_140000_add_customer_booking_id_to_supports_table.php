<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supports', function (Blueprint $table) {
            $table->foreignId('customer_booking_id')
                ->nullable()
                ->after('associate_id')
                ->constrained('customer_bookings')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('supports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customer_booking_id');
        });
    }
};
