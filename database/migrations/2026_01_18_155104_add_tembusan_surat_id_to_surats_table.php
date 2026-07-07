<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->foreignId('tembusan_surat_id')->nullable()->constrained('surats')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropForeign(['tembusan_surat_id']);
            $table->dropColumn('tembusan_surat_id');
        });
    }
};
