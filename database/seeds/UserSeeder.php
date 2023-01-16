<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->truncate();
       	$user = User::create([
            'nombre' => 'Admin',
            'apellido' => 'Admin',
            'email' => 'admin@admin.com',
            'enabled' => true,
            'password' => 'admin123',
          //'usuario' => 'admin',
       	]);
      
        $user->assignRole('Superadmin');

    }
}
