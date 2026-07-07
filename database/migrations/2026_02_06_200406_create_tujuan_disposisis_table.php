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
        Schema::create('tujuan_disposisis', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Contoh: "Wakil Rektor I", "Kepala BAAK"
            $table->integer('urutan')->default(0); // Untuk mengurutkan di dropdown
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tujuan_disposisis');
    }
};
