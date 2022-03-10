<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\LogSheet;
use App\Models\Vehicle;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class DataImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    public function rules(): array
    {
        return [
            '*.Log Sheet' => 'required|alpha_num',
            '*.Date' => 'required|date',
            '*.Invoice No' => 'required|alpha_num',
            '*.Inv. Date' => 'required|date',
            '*.Payer' => 'nullable|string',
            '*.Payer Name' => 'nullable|string',
            '*.Gross Wt.' => 'nullable|numeric',
            '*.Tprt Name' => 'nullable|string',
            '*.Container ID' => 'nullable|string',
            '*.Destination' => 'nullable|string',
            '*.No of Packs' => 'nullable|numeric',
            '*.DRIVER No' => 'nullable|string',
        ];
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            DB::table('raw_data_imports')->insert([
                'log_sheet' => $row['Log Sheet'],
                'date' => $row['Date'],
                'invoice_no' => $row['Invoice No'],
                'invoive_date' => $row['Inv. Date'],
                'payer' => $row['Payer'],
                'payer_name' => $row['Payer Name'],
                'gross_weight' => $row['Gross Wt.'],
                'transporter_name' => $row['Tprt Name'],
                'container_id' => $row['Container ID'],
                'destination' => $row['Destination'],
                'no_of_packs' => $row['No of Packs'],
                'driver_no' => $row['DRIVER No'],
            ]);

            $log_sheet = LogSheet::updateOrCreate(
                [
                    'log_sheet_no' => $row['log_sheet_no'],
                ],
                [
                    'date' => $row['date'],
                ]
            );

            $client_exists = Client::whereHas('user', function ($query) use ($row) {
                $query->where('name', $row['Payer Name']);
            })->count() > 0;
            $client_user = null;
            if (!$client_exists) {
                $client_user = User::factory(1)->create([
                    'name' => $row['Payer Name'],
                    'gender' => null,
                    'dob' => null,
                ]);

                $client_user->assignRole('client');
                $client_user->client()->create();
            }
            $client = $client_exists ? Client::whereHas('user', function ($query) use ($row) {
                $query->where('name', $row['Payer Name']);
            })->first() : $client_user->client()->first();

            $transporter_exists = User::whereHas('transporter', function ($query) use ($row) {
                $query->where('name', $row['Tprt Name']);
            })->count() > 0;
            $transporter_user = null;
            if (!$transporter_exists) {
                $transporter_user = User::factory(1)->create([
                    'name' => $row['Tprt Name'],
                    'gender' => null,
                    'dob' => null,
                ]);

                $transporter_user->assignRole('transporter');
                $transporter_user->transporter()->create();
            }
            $transporter = $transporter_exists ? User::whereHas('transporter', function ($query) use ($row) {
                $query->where('name', $row['Tprt Name']);
            })->first() : $transporter_user->transporter()->first();

            $driver_exists = User::whereHas('driver', function ($query) use ($row) {
                $query->where('phone', $row['DRIVER No']);
            })->count() > 0;
            $driver_user = null;

            if (!$driver_exists) {
                $driver_user = User::factory(1)->create([
                    'name' => $row['Tprt Name'],
                    'gender' => null,
                    'dob' => null,
                    'phone' => $row['DRIVER No'],
                ]);

                $driver_user->assignRole('driver');
                $driver_user->driver()->create();
            }
            $driver = $driver_exists ? User::whereHas('driver', function ($query) use ($row) {
                $query->where('phone', $row['DRIVER No']);
            })->first() : $driver_user->driver()->first();

            $vehicle = Vehicle::firstOrCreate([
                'vehicle_no' => $row['Container ID'],
            ], ['is_active' => true]);

            Invoice::updateOrCreate(
                [
                    'invoice_no' => $row['Invoice No'],
                ],
                [
                    'log_sheet_id' => $log_sheet->id,
                    'date' => $row['Inv. Date'],
                    'client_id' => $client->id,
                    'gross_weight' => $row['Gross Wt.'],
                    'transporter_id' => $transporter->id,
                    'vehicle_id' => $vehicle->id,
                    'destination' => $row['Destination'],
                    'driver_id' => $driver->id,
                    'no_of_packs' => $row['No of Packs'],
                    'invoive_date' => $row['invoive_date'],
                ]
            );
        }
    }
}
