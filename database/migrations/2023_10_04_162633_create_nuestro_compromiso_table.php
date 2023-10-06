<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNuestroCompromisoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nuestro_compromiso', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo')->index();
            $table->string('titulo');
            $table->string('imagen_home');
            $table->string('imagen_interna')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nuestro_compromiso');
    }
}
