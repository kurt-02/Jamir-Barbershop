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
        Schema::create('barber_service', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('barber_id')->constrained('barbers')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barber_service');
    }
};
