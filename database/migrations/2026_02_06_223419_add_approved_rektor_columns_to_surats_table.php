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
        Schema::table('surats', function (Blueprint $table) {
            $table->unsignedBigInteger('approved_rektor_by')->nullable()->after('paraf_wr_at');
            $table->timestamp('approved_rektor_at')->nullable()->after('approved_rektor_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropColumn(['approved_rektor_by', 'approved_rektor_at']);
        });
    }
};
