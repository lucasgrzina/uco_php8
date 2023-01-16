<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePackagingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packagings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('unidades')->default(1)->index();
            $table->decimal('alto',5,2);
            $table->decimal('largo',5,2);
            $table->decimal('ancho',5,2);
            $table->integer('peso');
            $table->boolean('enabled')->default(true)->index();
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
        Schema::drop('packagings');
        //Schema::drop('SINGULAR_NAME_translations'); //Cambiar por nombre de tabla
    }
}
