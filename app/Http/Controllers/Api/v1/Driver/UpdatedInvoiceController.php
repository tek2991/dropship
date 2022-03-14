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
     * API endpoint for driver's invoices history. If everything is okay, you'll get a 200 Status with response data in JSON format.
     * 
     * <aside class="notice">Returns empty data array [ ... ] id no invoice history exists.</aside>
     * 
     * @authenticated
     * 
     * @response status=200 scenario=Success {
     *      "data": [
     *          {
     *              "log_sheet_id": 1,
     *              "invoice_no": "1240072314",
     *              "date": "2021-12-28",
     *              "client_id": 1,
     *              "gross_weight": "4030",
     *              "no_of_packs": "100",
     *              "is_delivered": true,
     *              "updated_at": "2022-03-14T13:26:45.000000Z",
     *              "updated_by": {
     *                  "id": 1,
     *                  "name": "Admin",
     *                  "email": "tek2991@gmail.com",
     *                  "phone": "856d0f73-dcf9-3df4-b323-5a4a334087e9",
     *                  "alternate_phone": "NA",
     *                  "address": "4381 Estella Stravenue\nRunolfssonton, PA 09896-6519"
     *              },
     *              "remarks": "Delivered to recepient",
     *              "client": {
     *                  "id": 2,
     *                  "name": "B R Residency",
     *                  "email": "althea.murphy@example.net",
     *                  "phone": "be7a117d-868b-34ac-bedd-8c38e7fd53aa",
     *                  "alternate_phone": "NA",
     *                  "address": "NA"
     *              },
     *              "images": [
     *                  {
     *                      "folder": "invoices/1240072314/622f428f2966d_1647264399",
     *                      "filename": "doc_2.jpg",
     *                      "url": "http://dropship.test/storage/invoices/1240072314/622f428f2966d_1647264399/doc_2.jpg"
     *                  },
     *                  {
     *                      "folder": "invoices/1240072314/622f428f29748_1647264399",
     *                      "filename": "doc_1.jpg",
     *                      "url": "http://dropship.test/storage/invoices/1240072314/622f428f29748_1647264399/doc_1.jpg"
     *                  }
     *              ]
     *          }
     *      ]
     *  }
     */
    public function index(){
        $user = Auth::user();
        $invoices = $user->driver->invoices()->where('is_delivered', true)->with('clientUser', 'images', 'updatedByUser')->get();
        
        return InvoiceResource::collection($invoices);
    }
}
