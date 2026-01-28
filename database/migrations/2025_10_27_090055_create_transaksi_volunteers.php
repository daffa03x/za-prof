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
        if (!Schema::hasTable('transaksi_volunteers')) {
            Schema::create('transaksi_volunteers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('volunteer_id')->constrained('volunteers')->onDelete('cascade');
                $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_volunteers');
    }
};
