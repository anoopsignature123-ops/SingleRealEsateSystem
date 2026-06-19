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
        Schema::create('correspondence_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('primary_detail_id')->nullable()->constrained('primary_details')->cascadeOnDelete();
            $table->foreignId('secondary_detail_id')->nullable()->constrained('secondary_details')->cascadeOnDelete();
            $table->longText('correspondence_address')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('email')->nullable();
            $table->enum('id_proof_type', ['pancard', 'aadhar'])->nullable();
            $table->string('id_proof_number')->nullable();
            $table->string('occupation')->nullable();
            $table->string('nationality')->default('india');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('correspondence_details');
    }
};
