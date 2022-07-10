<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\DeliveryState;
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
        // Generate delivery states
        $delivery_states = DeliveryState::all();
        foreach ($delivery_states as $delivery_state) {
            Invoice::where('delivery_status', $delivery_state->name)
            ->update(['delivery_state_id' => $delivery_state->id]);
        }

        // Create default location as Guwahati
        $location = \App\Models\Location::create([
            'name' => 'Guwahati',
        ]);
        // Assign default location to all invoices
        $invoices = \App\Models\Invoice::all();
        foreach ($invoices as $invoice) {
            $invoice->location_id = $location->id;
            $invoice->save();
        }
        // Assign default location to all LogSheets
        $logSheets = \App\Models\LogSheet::all();
        foreach ($logSheets as $logSheet) {
            $logSheet->location_id = $location->id;
            $logSheet->save();
        }
        // Assign default location to all Clients
        $clients = \App\Models\Client::all();
        foreach ($clients as $client) {
            $client->locations()->syncWithoutDetaching([$location->id]);
        }
        // Assign default location to all Drivers
        $drivers = \App\Models\Driver::all();
        foreach ($drivers as $driver) {
            $driver->locations()->syncWithoutDetaching([$location->id]);
        }
        // Assign default location to all Transporters
        $transporters = \App\Models\Transporter::all();
        foreach ($transporters as $transporter) {
            $transporter->locations()->syncWithoutDetaching([$location->id]);
        }
        // Assign default location to all Vehicles
        $vehicles = \App\Models\Vehicle::all();
        foreach ($vehicles as $vehicle) {
            $vehicle->locations()->syncWithoutDetaching([$location->id]);
        }
        // Assign default location to all Imports
        $imports = \App\Models\Import::all();
        foreach ($imports as $import) {
            $import->location_id = $location->id;
            $import->save();
        }
        // Assign default location to all RawDataImports
        $rawDataImports = \App\Models\RawDataImport::all();
        foreach ($rawDataImports as $rawDataImport) {
            $rawDataImport->location_id = $location->id;
            $rawDataImport->save();
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
