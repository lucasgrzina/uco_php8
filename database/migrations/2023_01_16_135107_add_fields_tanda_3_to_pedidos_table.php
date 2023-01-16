<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsTanda3ToPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('nombre_fc')->nullable();
            $table->string('apellido_fc')->nullable();
            $table->string('dni_fc')->nullable();

            $table->string('direccion_fc')->nullable();
            $table->string('ciudad_fc')->nullable();
            $table->string('cp_fc')->nullable();
            $table->string('provincia_fc')->nullable();
            $table->integer('pais_id_fc')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['nombre_fc','apellido_fc','dni_fc','direccion_fc','ciudad_fc','cp_fc','provincia_fc','pais_id_fc']);
        });
    }
}
