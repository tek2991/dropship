<?php

namespace App\Http\Controllers\Admin;

use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\LogSheet;
use App\Models\Transporter;
use App\Http\Controllers\Controller;
use App\Http\Requests\cruds\UpdateLogSheetRequest;

class LogSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logSheets = LogSheet::withCount('invoices')->with('driverUser')->paginate();

        return view('admin.log-sheets.index', [
            'logSheets' => $logSheets,
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LogSheet  $logSheet
     * @return \Illuminate\Http\Response
     */
    public function show(LogSheet $logSheet)
    {
        return view('admin.log-sheets.show', [
            'logSheet' => $logSheet->load('driverUser', 'transporterUser', 'vehicle', 'transporter', 'driver'),
            'invoices' => $logSheet->invoices()->with('clientUser', 'client')->paginate(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param  \App\Models\LogSheet  $logSheet
     * @return \Illuminate\Http\Response 
     */
    public function edit(LogSheet $logSheet)
    {
        return view('admin.log-sheets.edit', [
            'logSheet' => $logSheet,
            'vehicles' => Vehicle::all(),
            'transporters' => Transporter::with('user')->get(),
            'drivers' => Driver::with('user')->get(),
        ]);
    }


    /**
     * Update the specified resource in storage.
     * @param  \App\Http\Requests\cruds\UpdateLogSheetRequest  $request
     * @param  \App\Models\LogSheet  $logSheet
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * */
    public function update(UpdateLogSheetRequest $request, LogSheet $logSheet){
        $logSheet->update($request->validated());
        return redirect()->route('admin.log-sheets.index')->with('message', 'Log Sheet: ' . $logSheet->id . ' updated successfully.');
    }
}
