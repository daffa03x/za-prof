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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('mitra', 100);
            $table->string('website', 100)->nullable();
            $table->boolean('status')->default(0);
            $table->dateTime('waktu_mulai'); 
            $table->dateTime('waktu_berakhir');
            $table->string('nama_tempat', 150);
            $table->string('alamat', 255);
            $table->string('kota', 100);
            $table->unsignedInteger('jumlah_tiket');
            $table->integer('harga');
            $table->text('deskripsi')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });        
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
