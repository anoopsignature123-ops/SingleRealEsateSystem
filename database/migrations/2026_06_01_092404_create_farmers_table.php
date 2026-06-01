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
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broker_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('caste')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pancard_number')->nullable();
            $table->string('aadhar_number')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};