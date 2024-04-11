<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Invoice;
use App\Models\Vehicle;
use App\Models\LogSheet;
use App\Models\Transporter;
use App\Models\DeliveryState;

use Illuminate\Support\Carbon;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class DataImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    public static $location_id = null;

    public function __construct($location_id)
    {
        self::$location_id = $location_id;
    }
 
    public function rules(): array
    {
        return [
            '*.log_sheet' => 'required|alpha_num',
            '*.date' => 'required|date',
            '*.invoice_no' => 'required|alpha_num',
            '*.inv_date' => 'required|date',
            '*.payer' => 'required|alpha_num',
            '*.payer_name' => 'required|string',
            '*.gross_wt' => 'required|numeric',
            '*.tprt_name' => 'required|string',
            '*.container_id' => 'required|string',
            '*.destination' => 'required|string',
            '*.no_of_packs' => 'required|numeric',
            '*.driver_no' => 'required|numeric',
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
                'location_id' => self::$location_id,
            ]);


            $client_exists = Client::where('client_number', $row['payer'])->exists();
            $client_user = null;
            if (!$client_exists) {
                $client_user = User::factory(1)->create([
                    'name' => $row['payer_name'],
                    'gender' => null,
                    'dob' => null,
                    'address' => 'NA',
                    'is_active' => true,
                ])->first();

                $client_user->assignRole('client');
                $client_user->client()->create([
                    'client_number' => $row['payer'],
                ]);
                $client_user->client->locations()->syncWithoutDetaching([self::$location_id]);
            } else {
                Client::firstWhere('client_number', $row['payer'])->user->update([
                    'name' => $row['payer_name'],
                    'is_active' => true,
                ]);
                $client_model = Client::firstWhere('client_number', $row['payer']);
                $client_model->locations()->syncWithoutDetaching([self::$location_id]);
            }
            $client = $client_exists ? Client::firstWhere('client_number', $row['payer']) : $client_user->client;


            $transporter_exists = Transporter::whereHas('user', function ($query) use ($row) {
                $query->where('name', $row['tprt_name']);
            })->exists();
            $transporter_user = null;
            if (!$transporter_exists) {
                $transporter_user = User::factory(1)->create([
                    'name' => $row['tprt_name'],
                    'gender' => null,
                    'dob' => null,
                    'address' => 'NA',
                    'is_active' => true,
                ])->first();

                $transporter_user->assignRole('transporter');
                $transporter_user->transporter()->create();
                $transporter_user->transporter->locations()->syncWithoutDetaching([self::$location_id]);
            } else {
                $transporter_model = Transporter::whereHas('user', function ($query) use ($row) {
                    $query->where('name', $row['tprt_name']);
                })->first();
                $transporter_model->locations()->syncWithoutDetaching([self::$location_id]);
            }
            $transporter = $transporter_exists ? Transporter::whereHas('user', function ($query) use ($row) {
                $query->where('name', $row['tprt_name']);
            })->first() : $transporter_user->transporter;

            $driver = null;
            if ($row['driver_no']) {
                $driver_exists = Driver::whereHas('user', function ($query) use ($row) {
                    $query->where('phone', $row['driver_no']);
                })->exists();
                $driver_user = null;
                if (!$driver_exists) {
                    $driver_user = User::factory(1)->create([
                        'name' => 'Driver_' . $row['driver_no'],
                        'email' => 'driver_' . $row['driver_no'] . '@dropship.test',
                        'gender' => null,
                        'dob' => null,
                        'phone' => $row['driver_no'],
                        'address' => 'NA',
                        'is_active' => true,
                    ])->first();
                    $driver_user->assignRole('driver');
                    $driver_user->driver()->create();
                    $driver_user->driver->locations()->syncWithoutDetaching([self::$location_id]);
                } else {
                    $driver_model = Driver::whereHas('user', function ($query) use ($row) {
                        $query->where('phone', $row['driver_no']);
                    })->first();
                    $driver_model->locations()->syncWithoutDetaching([self::$location_id]);
                }
                $driver = $driver_exists ? Driver::whereHas('user', function ($query) use ($row) {
                    $query->where('phone', $row['driver_no']);
                })->first() : $driver_user->driver;
            }


            $vehicle = Vehicle::firstOrCreate([
                'registration_number' => $row['container_id'],
            ], ['is_active' => true]);

            $vehicle->locations()->syncWithoutDetaching([self::$location_id]);


            $log_sheet = LogSheet::updateOrCreate(
                [
                    'log_sheet_no' => $row['log_sheet'],
                ],
                [
                    'date' => $date,
                    // To be reomved
                    'transporter_id' => $transporter->id,
                    'vehicle_id' => $vehicle->id,
                    'destination' => $row['destination'],
                    'driver_id' => $driver ? $driver->id : null,
                    'location_id' => self::$location_id,
                ]
                );


            $pending_delivery_state_id = DeliveryState::where('name', 'pending')->first()->id;
            $invoice = Invoice::updateOrCreate(
                [
                    'invoice_no' => $row['invoice_no'],
                ],
                [
                    'log_sheet_id' => $log_sheet->id,
                    'date' => $invoice_date,
                    'client_id' => $client->id,
                    'gross_weight' => $row['gross_wt'],
                    'no_of_packs' => $row['no_of_packs'],
                    'transporter_id' => $transporter->id,
                    'vehicle_id' => $vehicle->id,
                    'destination' => $row['destination'],
                    'driver_id' => $driver ? $driver->id : null,
                    'location_id' => self::$location_id,
                    'delivery_state_id' => $pending_delivery_state_id,
                ]
            );
        }
    }
}
