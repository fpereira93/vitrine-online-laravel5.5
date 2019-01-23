<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new App\User();
        $admin->name = 'Administrador';
        $admin->email = 'admin@ateliemariamodas.com';
        $admin->password = bcrypt('mariamodas');
        $admin->save();

        Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => config('auth.defaults.guard')
        ]);

        $admin->syncRoles(['superadmin']);
    }
}
