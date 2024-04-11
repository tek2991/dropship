<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\DeliveryState;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class generate_delivery_state_id extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $delivery_states = DeliveryState::all();

        foreach ($delivery_states as $delivery_state) {
            Invoice::where('delivery_status', $delivery_state->name)
            ->update(['delivery_state_id' => $delivery_state->id]);
        }
    }
}
