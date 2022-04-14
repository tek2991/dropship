<?php

namespace App\Http\Controllers\Api\v1\Driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\v1\InvoiceResource;

class UpdatedInvoiceController extends Controller
{
    /**
     * Updated Invoices
     * 
     * API endpoint for driver's invoices history. If everything is okay, you'll get a 200 Status with paginated response data in JSON format.
     * 
     * <aside class="notice">data { ... } contains a data [ ... ] array of Invoices with pagination data.</aside>
     * 
     * <aside class="notice">Returns empty data array [ ... ] if invoice history does not exists.</aside>
     * 
     * <aside class="notice">The links and meta objects contain pagination information</aside>
     * 
     * @authenticated
     * 
     * @response status=200 scenario=Success {"status": true, "message": "Invoices History", "data":{"data": [{"invoice_id": 6, "log_sheet_id": "6", "invoice_no": "1240069561", "date": "2021-12-28", "client_id": "6", "gross_weight": "286.688", "no_of_packs": "39", "is_delivered": true, "updated_at": "2022-04-14T14:03:41.000000Z", "updated_by":{"user_id": 23, "name": "Driver_1231479550", "email": "driver_1231479550@dropship.test", "phone": "1231479550", "alternate_phone": "NA", "address": "NA"}, "remarks": "The drivers remarks", "client":{"user_id": 13, "name": "Divyam Agencies", "email": "brakus.rocio@example.org", "phone": "68da9768-cd4b-329c-913e-88d2df0a4b92", "alternate_phone": "NA", "address": "NA"}, "images": []}], "links":{"first": "http://localhost:8000/api/v1/driver/updated-invoices?page=1", "last": null, "prev": null, "next": null}, "meta":{"current_page": 1, "from": 1, "path": "http://localhost:8000/api/v1/driver/updated-invoices", "per_page": 15, "to": 1}}}
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $invoices = InvoiceResource::collection(
                $user->driver->invoices()
                    ->where('is_delivered', true)
                    ->with('clientUser', 'images', 'updatedByUser')
                    ->simplePaginate()
            );
            $invoices = $invoices->response()->getData(); // Get the response data. Otherwise, json response does not include pagination data. 😓 
            return response()->json([
                'status' => true,
                'message' => 'Invoices History',
                'data' => $invoices
            ]);
        } catch (\Exception $e) {
            // 🧐 
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch invoices',
                'errors' => $e->getMessage(),
                'data' => []
            ], 200);
        }
    }
}
