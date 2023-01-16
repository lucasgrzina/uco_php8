<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVinosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vinos', function (Blueprint $table) {
            $table->increments('id');
            $table->char('coleccion',2)->default('FT')->index();
            $table->string('imagen')->nullable();            
            $table->decimal('peso',6,2)->default(0);
            $table->decimal('largo',6,2)->default(0);
            $table->decimal('ancho',6,2)->default(0);
            $table->decimal('alto',6,2)->default(0);
            $table->boolean('enabled')->default(true)->index();
            $table->integer('orden')->default(1)->index();
            $table->timestamps();
            $table->softDeletes();            
            $table->auditable();
        });

        Schema::create('vino_translations', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('vino_id')->unsigned(); //Cambiar por id
            $table->string('locale')->index();

            $table->string('titulo');
            $table->text('descripcion')->nullable();

            $table->unique(['vino_id','locale']);
        });         

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vinos');
        Schema::drop('vino_translations'); //Cambiar por nombre de tabla
    }
}
