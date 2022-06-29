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
        $location = \App\Models\Location::create([
            'name' => 'Guwahati',
        ]);

        $invoices = \App\Models\Invoice::all();
        foreach ($invoices as $invoice) {
            $invoice->location_id = $location->id;
            $invoice->save();
        }
    }
}
