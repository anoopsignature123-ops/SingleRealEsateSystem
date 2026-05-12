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
        Schema::create('plot_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('block_id')->constrained('blocks')->cascadeOnDelete();
            $table->foreignId('plot_type_id')->constrained('plot_types')->cascadeOnDelete();
            $table->string('location')->nullable();
            $table->string('number_of_plots')->nullable();
            $table->string('plot_number')->nullable();
            $table->string('plot_no_from')->nullable();
            $table->string('plot_no_to')->nullable();
            $table->string('plot_rate')->nullable();
            $table->string('plc_rate')->nullable();
            $table->string('plot_area')->nullable();
            $table->string('plot_width')->nullable();
            $table->string('plot_length')->nullable();
            $table->enum('status', ['available', 'booked', 'hold', 'registry'])->default('available');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plot_details');
    }
};
