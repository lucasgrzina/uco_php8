<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('foto')->nullable();
            $table->date('fecha');
            $table->char('visible_home',2)->index();
            $table->integer('orden')->default(1)->index();
            $table->boolean('enabled')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->auditable();
        });

        Schema::create('nota_translations', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('nota_id')->unsigned(); //Cambiar por id
            $table->string('locale')->index();

            $table->string('titulo')->nullable();
            $table->text('cuerpo')->nullable();

            $table->unique(['nota_id','locale']);
        });         

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notas');
        Schema::drop('nota_translations'); //Cambiar por nombre de tabla
    }
}
