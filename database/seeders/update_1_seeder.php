<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class update_1_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $invoices = \App\Models\Invoice::with('logSheet')->get();
        foreach ($invoices as $invoice) {
            $invoice->update([
                'transporter_id' => $invoice->logSheet->transporter_id,
                'vehicle_id' => $invoice->logSheet->vehicle_id,
                'destination' => $invoice->logSheet->destination,
                'driver_id' => $invoice->logSheet->driver_id,
            ]);
        }
    }
}
