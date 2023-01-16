<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAniadasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aniadas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('anio')->index();
            
            $table->unsignedInteger('vino_id')->index();
            $table->integer('stock')->default(0);
            $table->decimal('precio_pesos',15,2)->default(0);
            $table->decimal('precio_usd',15,2)->default(0);
            $table->string('sku')->index();
            $table->boolean('enabled')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->auditable();
        });

        Schema::create('aniada_translations', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('aniada_id')->unsigned(); //Cambiar por id
            $table->string('locale')->index();

            $table->string('ficha')->nullable();

            $table->unique(['aniada_id','locale']);
        });         

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('aniadas');
        Schema::drop('aniada_translations'); //Cambiar por nombre de tabla
    }
}
