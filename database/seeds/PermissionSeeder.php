<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
		\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('model_has_permissions')->truncate();
        \DB::table('role_has_permissions')->truncate();    	
		\DB::table('permissions')->truncate();
		\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $items = [
			['Usuarios',10],
			['Roles y Permisos',20],
        ];

        foreach ($items as $i => $value) 
        {
        	if (!Permission::whereGroupName($value[0])->first())
        	{
		       	Permission::create([
		       		'guard_name' => 'admin',
		       		'name' => str_slug('ver '.$value[0]),
		       		'group_name' => $value[0],
		       		'group_id' => ($i+1),
		       		'order' => $value[1],
		       		'action' => 'ver'
		       	]);
		       	Permission::create([
		       		'guard_name' => 'admin',
		       		'name' => str_slug('editar '.$value[0]),
		       		'group_name' => $value[0],
		       		'group_id' => ($i+1),
		       		'order' => $value[1],
		       		'action' => 'editar'
		       	]);	       	
	        }
        }
    }
}
