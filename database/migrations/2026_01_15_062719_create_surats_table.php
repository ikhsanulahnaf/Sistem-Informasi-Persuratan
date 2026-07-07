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
        // database/migrations/xxxx_xx_xx_create_surats_table.php
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat');
            $table->string('perihal');
            $table->text('isi_ringkas')->nullable();
            $table->string('pengirim');
            $table->string('penerima');
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->string('file_path'); // path ke file PDF/DOC
            $table->enum('status', [
                'diajukan',
                'menunggu_approval_wr',
                'ditolak_wr',
                'disetujui_wr',
                'ditandatangani',
                'selesai',
                'didisposisi'
            ])->default('diajukan');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
