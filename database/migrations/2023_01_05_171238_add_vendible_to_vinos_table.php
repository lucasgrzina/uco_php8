<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendibleToVinosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vinos', function (Blueprint $table) {
            $table->boolean('vendible')->default(true)->after('enabled')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vinos', function (Blueprint $table) {
            $table->dropColumn(['vendible']);
        });
    }
}
