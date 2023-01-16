<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePedidosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('registrado_id')->index();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->index();
            $table->string('dni')->nullable()->index();
            $table->string('cuit')->nullable();
            $table->string('razon_social')->nullable();

            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('provincia')->nullable();
            $table->integer('provincia_id')->nullable()->index();
            $table->integer('pais_id')->nullable()->index();
            $table->string('cp', 50)->nullable();
            
            $table->char('envio_retiro',1)->default('E')->index();
            $table->char('tipo_factura',2)->default('CF')->index();
            
            $table->integer('estado_id')->default(1)->index();
            $table->decimal('total_carrito', 15, 2)->default(0);
            $table->decimal('total_envio', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            
            $table->boolean('enabled')->default(true)->index();
            $table->auditable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pedidos');
        //Schema::drop('SINGULAR_NAME_translations'); //Cambiar por nombre de tabla
    }
}
