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
            $table->string('jenis_surat_keluar')->nullable()->after('jenis');
            // Opsional: hapus kolom 'file_path' jika tidak lagi upload manual
            // Tapi kita pertahankan dulu untuk backward compatibility
        });
    }

    public function down()
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropColumn('jenis_surat_keluar');
        });
    }
};
