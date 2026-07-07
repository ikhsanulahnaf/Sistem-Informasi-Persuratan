<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    public function down()
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->enum('status', [
                'diajukan',
                'menunggu_approval_wr',
                'ditolak_wr',
                'disetujui_wr',
                'ditandatangani',
                'selesai',
                'didisposisi',
                'draft'
            ])->default('diajukan');
        });
    }
};