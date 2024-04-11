<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryRemarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deliveryRemarks = [
            'Goods Received in OK Condition',
            'Goods Received in Damage Condition',
            'Goods Received in Shortage Condition',
            'Goods Received in Return Condition',
            'Other'
        ];

        foreach ($deliveryRemarks as $deliveryRemark) {
            \App\Models\DeliveryRemark::updateOrCreate([
                'remark' => $deliveryRemark,
            ]);
        }

        // All invoices with remark 'Goods Received in OK Condition' should be updated to 1
        \App\Models\Invoice::where('remarks', 'LIKE', 'Goods Received in OK Condition')->update(['delivery_remark_id' => 1]);

        // All invoices with remark 'Goods Received in Damage Condition' should be updated to 2
        \App\Models\Invoice::where('remarks', 'LIKE', 'Goods Received in Damage Condition')->update(['delivery_remark_id' => 2]);

        // All invoices with remark 'Goods Received in Shortage Condition' should be updated to 3
        \App\Models\Invoice::where('remarks', 'LIKE', 'Goods Received in Shortage Condition')->update(['delivery_remark_id' => 3]);

        // All invoices with remark 'Goods Received in Return Condition' should be updated to 4
        \App\Models\Invoice::where('remarks', 'LIKE', 'Goods Received in Return Condition')->update(['delivery_remark_id' => 4]);

        // All remaining invoices should be updated to 5
        \App\Models\Invoice::where('delivery_remark_id', null)->update(['delivery_remark_id' => 5]);
    }
}
