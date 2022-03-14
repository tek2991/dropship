<?php

namespace App\Http\Controllers\Api\v1\Driver;

use App\Http\Controllers\Controller;
use Auth;

class PendingInvoiceController extends Controller
{
    /**
     * Pending Invoices
     * 
     * API endpoint for drivers pending invoices. If everything is okay, you'll get a 200 Status with response data in JSON format.
     * 
     * Otherwise, the request will fail with a 401 error, and a JSON response with the error detail.
     * 
     * <aside class="notice">Logout API is common for all user levels.</aside>
     * 
     * @authenticated
     * 
     * @response status=200 scenario=Success {
     *      {
     *         "id": 1,
     *         "created_at": "2022-03-14T04:59:57.000000Z",
     *         "updated_at": "2022-03-14T04:59:57.000000Z",
     *         "log_sheet_id": 1,
     *         "invoice_no": "1240072314",
     *         "date": "2021-12-28",
     *         "client_id": 1,
     *         "gross_weight": "4030",
     *         "no_of_packs": "100",
     *         "is_delivered": 0,
     *         "updated_by": null,
     *         "laravel_through_key": 1
     *       }
     */
    public function index(){
        $user = Auth::user();
        $invoices = $user->driver->invoices()->where('is_delivered', false)->with('clientUser', 'images', 'updatedByUser')->get();
        return response()->json($invoices);
    }
}
