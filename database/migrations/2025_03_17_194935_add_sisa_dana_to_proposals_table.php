<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('proposals', function (Blueprint $table) {
        $table->integer('total_dana')->default(9000000); // total dana awal
        $table->integer('sisa_dana')->default(9000000); // sisa dana
    });
}

public function down()
{
    Schema::table('proposals', function (Blueprint $table) {
        $table->dropColumn(['total_dana', 'sisa_dana']);
    });
}
};
