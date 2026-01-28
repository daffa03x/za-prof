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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('invoice');
            $table->unsignedInteger('jumlah_tiket');
            $table->unsignedBigInteger('total_pembayaran');
            $table->string('name');
            $table->string('email');
            $table->string('telepon');
            $table->enum('status_pembayaran', ['Success', 'Failed', 'Pending']);
            $table->dateTime('tanggal_register');
            $table->dateTime('tanggal_pembayaran')->nullable();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->foreignId('voucher_id')->nullable()->constrained('kode_vouchers')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('invoice');
            $table->index('email');
            $table->index('status_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
