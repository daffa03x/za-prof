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
            $table->unsignedBigInteger('id_event');
            $table->foreign('id_event')->references('id')->on('events');
            $table->string('invoice');
            $table->integer('jumlah_tiket');
            $table->string('total_pembayaran', 15);
            $table->string('name');
            $table->string('email');
            $table->string('telepon');
            $table->enum('status_pembayaran', ['Success', 'Failed', 'Pending']);
            $table->dateTime('tanggal_register');
            $table->dateTime('tanggal_pembayaran')->nullable();
            $table->unsignedBigInteger('id_payment');
            $table->foreign('id_payment')->references('id')->on('payments');
            $table->timestamps();
            $table->softDeletes();
            
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
