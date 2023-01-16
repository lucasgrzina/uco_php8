<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contactos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email');
            $table->string('pais')->nullable();
            $table->unsignedInteger('pais_id')->nullable()->index();
            $table->string('tel_prefijo')->nullable();
            $table->string('tel_numero')->nullable();
            $table->text('mensaje');
            $table->boolean('recibir_info')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->auditable();
        });

        /*Schema::create('SINGULAR_NAME_translations', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('foreign_id')->unsigned(); //Cambiar por id
            $table->string('locale')->index();

            //$table->string('name')->unique();

            $table->unique(['foreign_id','locale']);
        });*/         

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contactos');
        //Schema::drop('SINGULAR_NAME_translations'); //Cambiar por nombre de tabla
    }
}
