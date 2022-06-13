<?php

namespace App\Http\Controllers\Admin;

use File;
use Storage;
use App\Models\Image;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Invoice;
use App\Models\Vehicle;
use App\Models\Transporter;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Http\Controllers\Controller;
use App\Models\DeliveryState;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::with('clientUser', 'client', 'logSheet')->paginate();
        return view('admin.invoices.index', [
            'invoices' => $invoices,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        return view('admin.invoices.show', [
            'invoice' => $invoice->load('clientUser', 'client', 'driverUser', 'transporterUser', 'vehicle', 'images'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        return view('admin.invoices.edit', [
            'invoice' => $invoice->load('clientUser', 'client', 'driverUser', 'transporterUser', 'vehicle', 'images'),
            'clients' => Client::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'delivery_status' => 'required|string|in:delivered,pending,cancelled',
            'remarks' => 'nullable|string|max:255',
            '*.image' => 'nullable|exists:temporary_files,folder',
        ]);

        $invoice->update([
            'client_id' => $request->client_id,
            'delivery_status' => $request->delivery_status,
            'delivery_state_id' => DeliveryState::where('name', $request->delivery_status)->first()->id,
            'remarks' => $request->remarks,
            'updated_by' => auth()->user()->id,
        ]);

        $images = $request->image;

        foreach ($images as $image) {
            if ($image) {
                $temporaryFile = TemporaryFile::firstWhere('folder', $image);
                Storage::move('uploads/tmp/' . $image . '/' . $temporaryFile->filename, 'public/invoices/' . $invoice->invoice_no . '/' . $image . '/' . $temporaryFile->filename);

                $imageModel = Image::create([
                    'folder' => 'invoices/' . $invoice->invoice_no . '/' . $image,
                    'filename' => $temporaryFile->filename,
                    'created_by' => auth()->user()->id,
                ]);

                $invoice->images()->save($imageModel);
                Storage::deleteDirectory('uploads/tmp/' . $image);
                $temporaryFile->delete();
            }
        }

        return redirect()->route('admin.invoices.show', $invoice)->with('message', 'Invoice Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     * @throws \Exception
     *
     */
    public function destroy(Invoice $invoice){
        $invoice->delete();
        return redirect()->route('admin.invoices.index')->with('message', 'Invoice Deleted Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroyImage(Request $request, Invoice $invoice)
    {
        $request->validate([
            'image_to_delete' => 'required|exists:images,id',
        ]);

        $image = Image::find($request->image_to_delete);
        $folder = $image->folder;
        Storage::deleteDirectory('public/' . $folder);
        $image->delete();

        return redirect()->route('admin.invoices.edit', $invoice)->with('message', 'Image Deleted Successfully.');
    }
}
