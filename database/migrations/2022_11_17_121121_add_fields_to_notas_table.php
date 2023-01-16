<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToNotasTable extends Migration
{
    public function up()
    {
        
        Schema::table('nota_translations', function (Blueprint $table) {
            $table->text('bajada')->nullable();
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nota_translations', function (Blueprint $table) {
            $table->dropColumn(['bajada']);
        });        
    }
}
