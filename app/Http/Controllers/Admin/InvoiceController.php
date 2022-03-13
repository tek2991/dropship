<?php

namespace App\Http\Controllers\Admin;

use App\Models\Image;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Http\Controllers\Controller;
use File;
use Storage;


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
            'invoice' => $invoice->load('clientUser', 'client', 'logSheet.driverUser', 'logSheet.transporterUser', 'logSheet.vehicle', 'images'),
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
            'invoice' => $invoice->load('clientUser', 'client', 'logSheet.driverUser', 'logSheet.transporterUser', 'logSheet.vehicle', 'images'),
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
            'is_delivered' => 'required|boolean',
            '*.image' => 'nullable|exists:temporary_files,folder',
        ]);

        $invoice->update([
            'is_delivered' => $request->is_delivered,
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