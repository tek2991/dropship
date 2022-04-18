<?php

namespace App\Http\Controllers\Api\v1\Driver;

use App\Models\Image;
use App\Models\Invoice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Driver\UploadInvoicePhotoRequest;

class UploadInvoicePhotoController extends Controller
{
    /**
     * Upload Invoice Photo
     * 
     * API endpoint for driver's to upload Invoice photo. If everything is okay, you'll get a 200 Status with response message in JSON format.
     * 
     * <aside class="notice">The <b>invoice_id</b> must be passed as <b>{invoice}</b> in the request url.</aside>
     * 
     * <aside class="notice">Upload only <b>one</b> photo per request. <b>Multiple</b> requests may be sent for additional photos.</aside>
     * 
     * @authenticated
     * 
     * @response status=200 scenario=Success {"status": true, "message": "Image uploaded successfully", "data": {}}
     */
    public function store(UploadInvoicePhotoRequest $request, Invoice $invoice)
    {
        try {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $folder = uniqid() . '_' . now()->timestamp;
            $file->storeAs('invoices/' . $invoice->invoice_no . '/' . $folder,  $filename, 'public');

            $imageModel = Image::create([
                'folder' => 'invoices/' . $invoice->invoice_no . '/' . $folder,
                'filename' => $filename,
                'created_by' => auth()->user()->id,
            ]);
            $invoice->images()->save($imageModel);
            return response()->json([
                'status' => true,
                'message' => 'Image uploaded successfully',
                'data' => (object)[],
            ]);
        } catch (\Exception $e) {
            // 🧐 
            return response()->json([
                'status' => false,
                'message' => 'File upload failed!',
                'errors' => $e->getMessage(),
                'data' => (object)[],
            ], 200);
        }
    }
}
