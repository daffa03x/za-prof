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
        if (!Schema::hasColumn('kode_vouchers', 'deleted_at')) {
            Schema::table('kode_vouchers', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (!Schema::hasColumn('volunteers', 'deleted_at')) {
            Schema::table('volunteers', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kode_vouchers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
