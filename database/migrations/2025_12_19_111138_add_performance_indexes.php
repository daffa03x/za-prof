<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add performance indexes to improve query speed.
 * 
 * These indexes optimize:
 * - Event lookup by slug (portal pages)
 * - Voucher validation by code and event
 * - Transaction lookup by invoice
 * - Common filter queries (status, dates)
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Events table indexes
        Schema::table('events', function (Blueprint $table) {
            // Slug lookup for portal pages
            if (!$this->indexExists('events', 'events_slug_index')) {
                $table->index('slug');
            }
            // Status + created_at for filtered listings
            if (!$this->indexExists('events', 'events_status_created_at_index')) {
                $table->index(['status', 'created_at']);
            }
        });

        // Voucher codes table indexes
        Schema::table('kode_vouchers', function (Blueprint $table) {
            // Code + event lookup for validation
            if (!$this->indexExists('kode_vouchers', 'kode_vouchers_kode_id_event_index')) {
                $table->index(['kode', 'id_event']);
            }
            // Status + expiry for active voucher queries
            if (!$this->indexExists('kode_vouchers', 'kode_vouchers_status_tanggal_kadaluarsa_index')) {
                $table->index(['status', 'tanggal_kadaluarsa']);
            }
        });

        // Transactions table indexes
        Schema::table('transaksis', function (Blueprint $table) {
            // Invoice lookup (frequently used for detail pages)
            if (!$this->indexExists('transaksis', 'transaksis_invoice_index')) {
                $table->index('invoice');
            }
            // Status + date for filtered reports
            if (!$this->indexExists('transaksis', 'transaksis_status_pembayaran_tanggal_register_index')) {
                $table->index(['status_pembayaran', 'tanggal_register']);
            }
        });

        // Payments table index
        Schema::table('payments', function (Blueprint $table) {
            // Status for active payment filtering
            if (!$this->indexExists('payments', 'payments_status_index')) {
                $table->index('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('kode_vouchers', function (Blueprint $table) {
            $table->dropIndex(['kode', 'id_event']);
            $table->dropIndex(['status', 'tanggal_kadaluarsa']);
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropIndex(['invoice']);
            $table->dropIndex(['status_pembayaran', 'tanggal_register']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }

    /**
     * Check if an index exists on a table.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = Schema::getIndexes($table);
        foreach ($indexes as $index) {
            if ($index['name'] === $indexName) {
                return true;
            }
        }
        return false;
    }
};
