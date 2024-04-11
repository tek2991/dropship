<?php

namespace App\Http\Controllers\Api\v1\Driver;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\DeliveryState;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Driver\UpdateInvoiceRequest;
use App\Models\DeliveryRemark;

class UpdateInvoiceController extends Controller
{
    /**
     * Update Invoice
     * 
     * API endpoint for driver's to update Invoice status. If everything is okay, you'll get a 200 Status with response message in JSON format.
     * 
     * <aside class="notice">The <b>invoice_id</b> must be passed as <b>{invoice}</b> in the request url.</aside>
     * <aside class="notice">The <b>delivery_status</b> must be one of these: <b>"delivered"</b> or <b>"pending"</b> or <b>"cancelled"</b> (case sensitive)</aside>
     * <aside class="notice">The <b>remarks</b> must be one of these: <b>"Goods Received in OK Condition"</b> or <b>"Goods Received in Damage Condition"</b> or <b>"Goods Received in Shortage Condition"</b> or <b>"Goods Received in Return Condition"</b> or <b> "Other" </b> </aside>
     * 
     * @authenticated
     * 
     * @response status=200 scenario=Success {"status": true, "message": "Invoice updated successfully.", "data": {}}
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {

        try {
            $delivery_remark = DeliveryRemark::where('remark', 'LIKE', $request->remarks)->first();
            $invoice->update([
                'delivery_status' => $request->delivery_status,
                'delivery_state_id' => DeliveryState::where('name', $request->delivery_status)->first()->id,
                'remarks' => $request->remarks,
                'updated_by' => auth()->user()->id,
                'delivery_remark_id' => $delivery_remark ? $delivery_remark->id : DeliveryRemark::where('remark', 'LIKE', 'Other')->first()->id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Invoice updated successfully.',
                'data' => (object)[],
            ]);
        } catch (\Exception $e) {
            // 🧐 
            return response()->json([
                'status' => false,
                'message' => 'Failed to update invoice.',
                // 'errors' => $e->getMessage(),
                'data' => (object)[]
            ], 200);
        }
    }
}
