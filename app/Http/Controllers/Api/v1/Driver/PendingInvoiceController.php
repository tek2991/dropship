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
     * API endpoint for driver's pending invoices. If everything is okay, you'll get a 200 Status with paginated response data in JSON format.
     * 
     * <aside class="notice">data [ ... ] contains array of Invoices</aside>
     * 
     * <aside class="notice">Returns empty data array [ ... ] if there are no pending invoices.</aside>
     * 
     * <aside class="notice">The links and meta objects contain pagination information</aside>
     * 
     * @authenticated
     * 
     * @response status=200 scenario=Success {
     *      "data": [
     *            {
     *                "invoice_id": 1,
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
     *                    "user_id": 156,
     *                    "name": "VIJAY ENTERPRISES",
     *                    "email": "lane08@example.org",
     *                    "phone": "a8eb49ba-75e3-3177-b60c-30d76fa0746a",
     *                    "alternate_phone": "NA",
     *                    "address": "NA"
     *                },
     *                "images": []
     *            },
     *          ],
     *         "links": {
     *             "first": "http://dropship.test/api/v1/driver/pending-invoices?page=1",
     *             "last": null,
     *             "prev": null,
     *             "next": null
     *         },
     *         "meta": {
     *             "current_page": 1,
     *             "from": 1,
     *             "path": "http://dropship.test/api/v1/driver/pending-invoices",
     *             "per_page": 15,
     *             "to": 1
     *         }
     *      }
     */
    public function index(){
        $user = Auth::user();
        $invoices = $user->driver->invoices()->where('is_delivered', false)->with('clientUser', 'images', 'updatedByUser')->simplePaginate();
        
        return InvoiceResource::collection($invoices);
    }
}
