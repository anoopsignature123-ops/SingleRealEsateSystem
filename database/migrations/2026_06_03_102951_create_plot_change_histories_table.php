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
        Schema::create('plot_change_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_booking_id')->constrained('customer_bookings')->cascadeOnDelete();
            $table->foreignId('plot_sale_detail_id')->constrained('plot_sale_details')->cascadeOnDelete();
            $table->foreignId('old_project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('old_block_id')->nullable()->constrained('blocks')->nullOnDelete();
            $table->foreignId('old_plot_detail_id')->nullable()->constrained('plot_details')->nullOnDelete();
            $table->foreignId('new_project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('new_block_id')->nullable()->constrained('blocks')->nullOnDelete();
            $table->foreignId('new_plot_detail_id')->nullable()->constrained('plot_details')->nullOnDelete();
            $table->decimal('old_plot_rate', 12, 2)->default(0);
            $table->decimal('old_plot_area', 12, 2)->default(0);
            $table->decimal('old_plot_cost', 12, 2)->default(0);
            $table->decimal('old_plc_amount', 12, 2)->default(0);
            $table->decimal('old_total_plot_cost', 12, 2)->default(0);
            $table->decimal('new_plot_rate', 12, 2)->default(0);
            $table->decimal('new_plot_area', 12, 2)->default(0);
            $table->decimal('new_plot_cost', 12, 2)->default(0);
            $table->decimal('new_plc_amount', 12, 2)->default(0);
            $table->decimal('new_total_plot_cost', 12, 2)->default(0);
            $table->decimal('total_paid_amount', 12, 2)->default(0);
            $table->decimal('old_due_amount', 12, 2)->default(0);
            $table->decimal('new_due_amount', 12, 2)->default(0);
            $table->decimal('difference_amount', 12, 2)->default(0);
            $table->date('change_date')->nullable();
            $table->text('change_reason')->nullable();
            $table->text('remark')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plot_change_histories');
    }
};
