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
            $table->json('pertimbangan')->nullable();
            $table->text('dasar')->nullable();
            $table->json('untuk')->nullable();
        });
    }

    public function down()
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropColumn(['pertimbangan', 'dasar', 'untuk']);
        });
    }
};
