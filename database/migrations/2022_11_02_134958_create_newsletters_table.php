<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewslettersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->index();
            $table->timestamps();
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
        Schema::drop('newsletters');
        //Schema::drop('SINGULAR_NAME_translations'); //Cambiar por nombre de tabla
    }
}
