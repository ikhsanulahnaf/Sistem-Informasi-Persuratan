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
        // database/migrations/xxxx_xx_xx_create_digital_signatures_table.php
        Schema::create('digital_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained()->onDelete('cascade');
            $table->foreignId('signer_id')->constrained('users')->onDelete('cascade'); // Rektor
            $table->string('algorithm')->default('ECDSA'); // sesuai minat Anda
            $table->text('public_key');
            $table->text('signature_data'); // hex atau base64 dari signature
            $table->string('signed_file_path'); // file surat yang sudah ditandatangani
            $table->timestamp('signed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_signatures');
    }
};
