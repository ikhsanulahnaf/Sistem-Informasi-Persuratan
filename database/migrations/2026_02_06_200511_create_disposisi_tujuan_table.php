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
        Schema::create('disposisi_tujuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disposisi_id')->constrained()->onDelete('cascade');
            $table->foreignId('tujuan_disposisi_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['disposisi_id', 'tujuan_disposisi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisi_tujuan');
    }
};
