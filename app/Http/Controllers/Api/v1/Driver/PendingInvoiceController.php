<?php

namespace App\Http\Controllers\Api\v1\Driver;

use Auth;
use App\Models\DeliveryState;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\InvoiceResource;

class PendingInvoiceController extends Controller
{
    /**
     * Pending Invoices
     * 
     * API endpoint for the authenticated driver's pending invoices. If everything is okay, you'll get a 200 Status with paginated response data in JSON format.
     * 
     * <aside class="notice">data { ... } contains a data [ ... ] array of Invoices with pagination data.</aside>
     * 
     * <aside class="notice">Returns empty data array [ ... ] if there are no pending invoices.</aside>
     * 
     * <aside class="notice">The links and meta objects contain pagination information</aside>
     * 
     * @authenticated
     * 
     * @response status=200 scenario=Success {"status": true, "message": "Pending Invoices", "data":{"data": [{"invoice_id": 7, "log_sheet_id": "6", "invoice_no": "1240070535", "date": "2021-12-28", "client_id": "7", "gross_weight": "42.2", "no_of_packs": "3", "delivery_status": "Pending", "updated_at": "2022-04-14T09:16:26.000000Z", "updated_by": null, "remarks": null, "client":{"user_id": 15, "name": "RAVI UDYOG", "email": "vberge@example.com", "phone": "6990de17-80ed-34c4-a2b9-fe236e8303b7", "alternate_phone": "NA", "address": "NA"}, "images": []}], "links":{"first": "http://localhost:8000/api/v1/driver/pending-invoices?page=1", "last": null, "prev": null, "next": null}, "meta":{"current_page": 1, "from": 1, "path": "http://localhost:8000/api/v1/driver/pending-invoices", "per_page": 15, "to": 5}}}
     */
    public function index()
    {
        $pending_delivery_state_id = DeliveryState::STATE_PENDING;
        try {
            $user = Auth::user();
            $invoices = InvoiceResource::collection(
                $user->driver->invoices()
                    ->whereIn('delivery_state_id', [$pending_delivery_state_id])
                    ->with('clientUser', 'images', 'updatedByUser')
                    ->paginate(15)
            );

            $invoices = $invoices->response()->getData(); // Get the response data. Otherwise, json response does not include pagination data. 😓 

            return response()->json([
                'status' => true,
                'message' => 'Pending Invoices',
                'data' => $invoices,
            ]);
        } catch (\Exception $e) {
            // 🧐 
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch pending invoices',
                // 'errors' => $e->getMessage(),
                'data' => (object)[],
            ], 200);
        }
    }

    /**
     * All Pending Invoices
     * 
     * API endpoint for authenticated driver's all pending invoices. If everything is okay, you'll get a 200 Status with paginated response data in JSON format.
     * 
     * <aside class="notice">data { ... } contains a data [ ... ] array of all pending Invoices</aside>
     * 
     * <aside class="notice">Returns empty data array [ ... ] if there are no pending invoices.</aside>
     * 
     * @authenticated
     * 
     * @response status=200 scenario=Success {"status":true,"message":"All Pending Invoices","data":[{"invoice_id":13,"log_sheet_id":10,"invoice_no":"1240070556","date":"2021-12-28","client_id":13,"gross_weight":"158.28","no_of_packs":"19","delivery_status":"Pending","updated_at":"2022-07-13T05:37:34.000000Z","updated_by":null,"remarks":null,"client":{"user_id":26,"name":"SHREE GANESH TRADERS","email":"antoinette62@example.org","phone":"990cabce-366d-37df-a10a-96ffabe236bf","alternate_phone":"NA","address":"NA"},"images":[],"transporter":"ASHA ROAD CARRIERS","vehicle":"AS01MC0451","driver":"Driver_1004786257","destination":"GWT LOCAL"}]}
     */
    public function all()
    {
        $pending_delivery_state_id = DeliveryState::STATE_PENDING;
        try {
            $user = Auth::user();
            $invoices = InvoiceResource::collection(
                $user->driver->invoices()
                    ->whereIn('delivery_state_id', [$pending_delivery_state_id])
                    ->with('clientUser', 'images', 'updatedByUser')
                    ->get()
            );

            return response()->json([
                'status' => true,
                'message' => 'All Pending Invoices',
                'data' => $invoices,
            ]);
        } catch (\Exception $e) {
            // 🧐 
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch pending invoices',
                // 'errors' => $e->getMessage(),
                'data' => (object)[],
            ], 200);
        }
    }
}
