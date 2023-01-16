<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsTanda1ToPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('pedidos', function (Blueprint $table) {
            $table->decimal('total_carrito_usd', 15, 2)->default(0);
            $table->decimal('total_envio_usd', 15, 2)->default(0);
            $table->decimal('total_usd', 15, 2)->default(0);
            $table->decimal('cotizacion_usd', 7, 2)->default(0);
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
            $table->dropColumn(['total_carrito_usd','total_envio_usd','total_usd','cotizacion_usd']);
        });        
    }
}
