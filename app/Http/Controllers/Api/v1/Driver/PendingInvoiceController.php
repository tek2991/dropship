<?php

namespace App\Http\Controllers\Api\v1\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\InvoiceResource;
use Auth;

class PendingInvoiceController extends Controller
{
    /**
     * Pending Invoices
     * 
     * API endpoint for driver's pending invoices. If everything is okay, you'll get a 200 Status with response data in JSON format.
     * 
     * <aside class="notice">Returns empty data array [ ... ] id not pending invoices.</aside>
     * 
     * @authenticated
     * 
     * @response status=200 scenario=Success {
     *      "data": [
     *            {
     *                "log_sheet_id": 1,
     *                "invoice_no": "1240077996",
     *                "date": "2021-12-28",
     *                "client_id": 88,
     *                "gross_weight": "6.12",
     *                "no_of_packs": "2",
     *                "is_delivered": false,
     *                "updated_at": "2022-03-14T09:09:52.000000Z",
     *                "updated_by": null,
     *                "remarks": null,
     *                "client": {
     *                    "id": 156,
     *                    "name": "VIJAY ENTERPRISES",
     *                    "email": "lane08@example.org",
     *                    "phone": "a8eb49ba-75e3-3177-b60c-30d76fa0746a",
     *                    "alternate_phone": "NA",
     *                    "address": "NA"
     *                },
     *                "images": []
     *            },
     *          ]
     *      }
     */
    public function index(){
        $user = Auth::user();
        $invoices = $user->driver->invoices()->where('is_delivered', false)->with('clientUser', 'images', 'updatedByUser')->get();
        
        return InvoiceResource::collection($invoices);
    }
}
