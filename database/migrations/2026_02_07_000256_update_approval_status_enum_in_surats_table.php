<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update ENUM untuk menambahkan status baru
        DB::statement("ALTER TABLE surats MODIFY COLUMN approval_status ENUM(
            'draft',
            'pending_wr',
            'approved_wr',
            'rejected_wr',
            'pending_rektor',
            'waiting_rektor_approval',
            'approved_rektor',
            'rejected_rektor',
            'signed_rektor',
            'numbered',
            'archived',
            'returned',
            'didisposisi'
        ) DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback ke ENUM lama
        DB::statement("ALTER TABLE surats MODIFY COLUMN approval_status ENUM(
            'draft',
            'pending_wr',
            'approved_wr',
            'rejected_wr',
            'pending_rektor',
            'signed_rektor',
            'numbered',
            'archived',
            'returned',
            'didisposisi'
        ) DEFAULT 'draft'");
    }
};
