<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLegadosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legados', function (Blueprint $table) {
            $table->increments('id');
            $table->string('foto')->nullable();
            $table->integer('orden')->default(1)->index();
            $table->boolean('enabled')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
            
            $table->auditable();
        });

        Schema::create('legado_translations', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('legados_id')->unsigned(); //Cambiar por id
            $table->string('locale')->index();

            $table->string('titulo')->nullable();
            $table->text('cuerpo')->nullable();

            $table->string('boton_titulo')->nullable();
            $table->string('boton_url')->nullable();

            $table->unique(['legados_id','locale']);
        });     

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('legados');
        Schema::drop('legado_translations'); //Cambiar por nombre de tabla
    }
}
