<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHomeSlidersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_sliders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('imagen_mobile')->nullable();
            $table->string('imagen_desktop')->nullable();
            $table->text('video')->nullable();
            $table->integer('orden')->default(1)->index();
            $table->boolean('enabled')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->auditable();
        });

        Schema::create('home_slider_translations', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('home_slider_id')->unsigned(); //Cambiar por id
            $table->string('locale')->index();

            $table->string('titulo')->nullable();
            $table->string('subtitulo')->nullable();
            $table->string('boton_titulo')->nullable();
            $table->string('boton_url')->nullable();

            $table->unique(['home_slider_id','locale']);
        });      

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('home_sliders');
        //Schema::drop('SINGULAR_NAME_translations'); //Cambiar por nombre de tabla
    }
}
