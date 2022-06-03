<?php

namespace Database\Seeders;

use App\Models\DeliveryState;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $delivery_states = [
            'pending',
            'delivered',
            'cancelled',
        ];

        foreach ($delivery_states as $delivery_state) {
            DeliveryState::create([
                'name' => $delivery_state,
            ]);
        }
    }
}
