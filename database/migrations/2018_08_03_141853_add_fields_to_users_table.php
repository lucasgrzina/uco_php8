<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //$table->integer('role_id')->unsigned()->index();
            $table->string('nombre',100)->index()->after('id');
            $table->string('apellido',100)->index()->after('nombre');
            $table->boolean('enabled')->default(true)->after('apellido');
            $table->boolean('perm_cat')->default(true)->after('enabled');
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->auditable();
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nombre','apellido','fecha_nac','nro_celular','pais_id','usuario','enabled','perm_cat','deleted_at']);
            $table->dropAuditable();
        });        
    }
}
