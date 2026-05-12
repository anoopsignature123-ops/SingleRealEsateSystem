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
        Schema::create('customer_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('primary_detail_id')->nullable()->constrained('primary_details')->cascadeOnDelete();
            $table->foreignId('secondary_detail_id')->nullable()->constrained('secondary_details')->cascadeOnDelete();
            $table->boolean('dl')->default(false);
            $table->boolean('aadhar')->default(false);
            $table->boolean('voter_id')->default(false);
            $table->boolean('other')->default(false);
            $table->string('dl_file')->nullable();
            $table->string('aadhar_file')->nullable();
            $table->string('voter_id_file')->nullable();
            $table->string('other_file')->nullable();
            $table->string('profile_picture')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_documents');
    }
};
