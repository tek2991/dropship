<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogSheet;

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
            'logSheet' => $logSheet->load('driverUser', 'transporterUser', 'vehicle'),
            'invoices' => $logSheet->invoices()->with('clientUser', 'client')->paginate(),
        ]);
    }
}
