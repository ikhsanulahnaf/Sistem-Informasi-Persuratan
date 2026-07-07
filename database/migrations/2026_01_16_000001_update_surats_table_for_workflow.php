<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            // Tambah kolom untuk workflow approval yang lebih detail
            $table->enum('approval_status', [
                'draft',
                'pending_wr',
                'approved_wr',
                'rejected_wr',
                'pending_rektor',
                'signed_rektor',
                'numbered',
                'archived',
                'returned'
            ])->default('draft')->after('status');

            // Kolom untuk paraf WR dan tanda tangan Rektor
            $table->timestamp('paraf_wr_at')->nullable();
            $table->foreignId('paraf_wr_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamp('signed_rektor_at')->nullable();
            $table->foreignId('signed_rektor_by')->nullable()->constrained('users')->onDelete('set null');

            // Nomor surat (akan diisi oleh rektor saat numbering)
            $table->string('nomor_urut')->nullable()->unique();

            // Kolom untuk tracking revisi
            $table->integer('revision_count')->default(0);
            $table->text('revision_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropColumn([
                'approval_status',
                'paraf_wr_at',
                'paraf_wr_by',
                'signed_rektor_at',
                'signed_rektor_by',
                'nomor_urut',
                'revision_count',
                'revision_notes'
            ]);
        });
    }
};
