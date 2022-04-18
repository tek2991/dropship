<?php

namespace App\Http\Controllers\Api\v1\Driver;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Driver\UpdateInvoiceRequest;

class UpdateInvoiceController extends Controller
{
    /**
     * Update Invoice
     * 
     * API endpoint for driver's to update Invoice status. If everything is okay, you'll get a 200 Status with response message in JSON format.
     * 
     * <aside class="notice">The <b>invoice_id</b> must be passed as <b>{invoice}</b> in the request url.</aside>
     * 
     * @authenticated
     * 
     * @response status=200 scenario=Success {"status": true, "message": "Invoice updated successfully.", "data": {}}
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {

        try {
            $invoice->update([
                'is_delivered' => $request->is_delivered,
                'remarks' => $request->remarks,
                'updated_by' => auth()->user()->id,
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
                'errors' => $e->getMessage(),
                'data' => (object)[]
            ], 200);
        }
    }
}
