<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Invoice;
use App\Models\Vehicle;
use App\Models\LogSheet;
use App\Models\Transporter;
use Illuminate\Support\Carbon;

use Illuminate\Support\Collection;

use Illuminate\Support\Facades\DB;
// use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class DataImport implements ToCollection, WithHeadingRow
{
    use Importable;

    public function rules(): array
    {
        return [
            '*.log_sheet' => 'required|alpha_num',
            '*.date' => 'required|date',
            '*.invoice_no' => 'required|alpha_num',
            '*.inv_date' => 'required|date',
            '*.payer' => 'nullable|string',
            '*.payer_name' => 'nullable|string',
            '*.gross_wt.' => 'nullable|numeric',
            '*.tprt_name' => 'nullable|string',
            '*.container_id' => 'nullable|string',
            '*.destination' => 'nullable|string',
            '*.no_of_packs' => 'nullable|numeric',
            '*.driver_no' => 'nullable|string',
        ];
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $date = Carbon::createFromFormat('d.m.Y', $row['date'])->format('Y-m-d');
            $invoice_date = Carbon::createFromFormat('d.m.Y', $row['inv_date'])->format('Y-m-d');

            DB::table('raw_data_imports')->insert([
                'log_sheet' => $row['log_sheet'],
                'date' => $date,
                'invoice_no' => $row['invoice_no'],
                'invoice_date' => $invoice_date,
                'payer' => $row['payer'],
                'payer_name' => $row['payer_name'],
                'gross_weight' => $row['gross_wt'],
                'transporter_name' => $row['tprt_name'],
                'container_id' => $row['container_id'],
                'destination' => $row['destination'],
                'no_of_packs' => $row['no_of_packs'],
                'driver_no' => $row['driver_no'],
            ]);

            $log_sheet = LogSheet::updateOrCreate(
                [
                    'log_sheet_no' => $row['log_sheet'],
                ],
                [
                    'date' => $date,
                ]
            );






            $client_exists = Client::whereHas('user', function ($query) use ($row) {
                $query->where('name', $row['payer_name']);
            })->count() > 0;
            $client_user = null;
            if (!$client_exists) {
                $client_user = User::factory(1)->create([
                    'name' => $row['payer_name'],
                    'gender' => null,
                    'dob' => null,
                    'address' => 'NA'
                ])->first();

                $client_user->assignRole('client');
                $client_user->client()->create();
            }
            $client = $client_exists ? Client::whereHas('user', function ($query) use ($row) {
                $query->where('name', $row['payer_name']);
            })->first() : $client_user->client;







            $transporter_exists = Transporter::whereHas('user', function ($query) use ($row) {
                $query->where('name', $row['tprt_name']);
            })->count() > 0;
            $transporter_user = null;
            if (!$transporter_exists) {
                $transporter_user = User::factory(1)->create([
                    'name' => $row['tprt_name'],
                    'gender' => null,
                    'dob' => null,
                    'address' => 'NA'
                ])->first();

                $transporter_user->assignRole('transporter');
                $transporter_user->transporter()->create();
            }


            $transporter = $transporter_exists ? Transporter::whereHas('user', function ($query) use ($row) {
                $query->where('name', $row['tprt_name']);
            })->first() : $transporter_user->transporter;










            $driver_exists = Driver::whereHas('user', function ($query) use ($row) {
                $query->where('phone', $row['driver_no']);
            })->count() > 0;

            $driver_user = null;

            if (!$driver_exists) {
                $driver_user = User::factory(1)->create([
                    'name' => 'Driver_' . $row['driver_no'],
                    'email' => 'driver_' . $row['driver_no'] . '@dropship.test',
                    'gender' => null,
                    'dob' => null,
                    'phone' => $row['driver_no'],
                    'address' => 'NA'
                ])->first();

                $driver_user->assignRole('driver');
                $driver_user->driver()->create();
            }
            $driver = $driver_exists ? Driver::whereHas('user', function ($query) use ($row) {
                $query->where('phone', $row['driver_no']);
            })->first() : $driver_user->driver;









            $vehicle = Vehicle::firstOrCreate([
                'registration_number' => $row['container_id'],
            ], ['is_active' => true]);








            Invoice::updateOrCreate(
                [
                    'invoice_no' => $row['invoice_no'],
                ],
                [
                    'log_sheet_id' => $log_sheet->id,
                    'date' => $invoice_date,
                    'client_id' => $client->id,
                    'gross_weight' => $row['gross_wt'],
                    'transporter_id' => $transporter->id,
                    'vehicle_id' => $vehicle->id,
                    'destination' => $row['destination'],
                    'driver_id' => $driver->id,
                    'no_of_packs' => $row['no_of_packs'],
                ]
            );
        }
    }
}
