<?php

namespace App\Http\Controllers\Admin;

use App\Models\Image;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


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
            'invoice' => $invoice->load('clientUser', 'client', 'logSheet.driverUser', 'logSheet.transporterUser', 'logSheet.vehicle'),
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
            'invoice' => $invoice->load('clientUser', 'client', 'logSheet.driverUser', 'logSheet.transporterUser', 'logSheet.vehicle'),
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
                Storage::move('uploads/tmp/' . $image . '/' . $temporaryFile->filename, 'invoices/' . $invoice->id . '/' . $image . '/' . $temporaryFile->filename);

                $image = Image::create([
                    'url' => 'invoices/' . $invoice->id . '/' . $image . '/' . $temporaryFile->filename,
                    'created_by' => auth()->user()->id,
                ]);

                $invoice->images()->save($image);

                Storage::deleteDirectory('uploads/tmp/' . $request->file);
                $temporaryFile->delete();
            }
        }

        return redirect()->route('admin.invoices.index')->with('message', 'Invoice Updated Successfully.');
    }
}
