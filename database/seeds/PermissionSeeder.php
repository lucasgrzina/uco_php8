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
			['Usuarios'],
			['Roles y Permisos'],
            ['Registrados'],
            ['Legados'],
            ['Nuestro compromiso'],
            ['Notas'],
            ['Sliders'],
            ['Newsletters'],
            ['Contactos'],
            ['Vinos'],
            ['Pedidos'],
            ['Packagings'],
            ['Configuraciones'],
        ];

        foreach ($items as $i => $value)
        {
        	if (!Permission::whereGroupName($value[0])->first())
        	{
		       	Permission::create([
		       		'guard_name' => 'admin',
		       		'name' => \Str::slug('ver '.$value[0]),
		       		'group_name' => $value[0],
		       		'group_id' => ($i+1),
		       		'order' => ($i+1),
		       		'action' => 'ver'
		       	]);
		       	Permission::create([
		       		'guard_name' => 'admin',
		       		'name' => \Str::slug('editar '.$value[0]),
		       		'group_name' => $value[0],
		       		'group_id' => ($i+1),
		       		'order' => ($i+1),
		       		'action' => 'editar'
		       	]);
	        }
        }
    }
}
