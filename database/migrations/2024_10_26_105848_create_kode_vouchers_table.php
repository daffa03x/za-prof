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
        Schema::create('kode_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('name_voucher')->unique();
            $table->string('kode')->unique();
            $table->unsignedInteger('nilai_diskon');
            $table->unsignedInteger('kuota');
            $table->unsignedInteger('digunakan')->default(0);
            $table->date('tanggal_kadaluarsa');
            $table->boolean('status')->default(false);
            $table->timestamps();

            $table->index('tanggal_kadaluarsa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kode_vouchers');
    }
};
