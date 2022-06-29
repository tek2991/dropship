<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class update_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $location = \App\Models\Location::create([
            'name' => 'Guwahati',
        ]);
        $invoices = \App\Models\Invoice::all();
        foreach ($invoices as $invoice) {
            $invoice->location_id = $location->id;
            $invoice->save();
        }
        $logSheets = \App\Models\LogSheet::all();
        foreach ($logSheets as $logSheet) {
            $logSheet->location_id = $location->id;
            $logSheet->save();
        }

        Permission::create(['name' => 'invoice_crud']);
        Permission::create(['name' => 'logsheet_crud']);
        Permission::create(['name' => 'location_crud']);
        Permission::create(['name' => 'manager_crud']);

        $role = Role::where('name', 'admin')->first();
        $role->givePermissionTo([
            'invoice_crud',
            'logsheet_crud',
            'location_crud',
            'manager_crud',
        ]);
        $role = Role::create(['name' => 'manager']);
    }
}
