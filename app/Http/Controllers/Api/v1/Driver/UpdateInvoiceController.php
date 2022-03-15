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
     * @response status=200 scenario=Success {
     *     "message": "Invoices updated successfully.",
     *  }
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice){

        $invoice->update([
            'is_delivered' => $request->is_delivered,
            'remarks' => $request->remarks,
            'updated_by' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => 'Invoice updated successfully',
        ]);
    }
}
