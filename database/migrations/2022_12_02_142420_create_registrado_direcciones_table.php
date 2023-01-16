<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistradoDireccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrado_direcciones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('registrado_id')->index();
            $table->string('nombre');
            $table->string('apellido')->nullable();
            $table->string('calle',255);
            $table->string('ciudad')->nullable();
            $table->string('provincia')->nullable();
            $table->unsignedInteger('pais_id')->index();
            $table->string('cp',10)->index();
            $table->boolean('principal')->defualt(false)->index();
            $table->timestamps();
            $table->softDeletes();            
            $table->auditable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registrado_direcciones');
    }
}
