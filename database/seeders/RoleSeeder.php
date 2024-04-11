<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // create permissions
        Permission::create(['name' => 'driver_crud']);
        Permission::create(['name' => 'transporter_crud']);
        Permission::create(['name' => 'vehicle_crud']);
        Permission::create(['name' => 'client_crud']);
        Permission::create(['name' => 'invoice_crud']);
        Permission::create(['name' => 'logsheet_crud']);
        Permission::create(['name' => 'location_crud']);
        Permission::create(['name' => 'manager_crud']);

        // create director roles and assign permissions
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo([
            'driver_crud',
            'transporter_crud',
            'vehicle_crud',
            'client_crud',
            'invoice_crud',
            'logsheet_crud',
            'location_crud',
            'manager_crud',
        ]);

        $role = Role::create(['name' => 'driver']);
        $role = Role::create(['name' => 'transporter']);
        $role = Role::create(['name' => 'client']);
        $role = Role::create(['name' => 'manager']);
    }
}
