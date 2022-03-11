<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogSheet;
use Illuminate\Http\Request;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LogSheet  $logSheet
     * @return \Illuminate\Http\Response
     */
    public function edit(LogSheet $logSheet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LogSheet  $logSheet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LogSheet $logSheet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LogSheet  $logSheet
     * @return \Illuminate\Http\Response
     */
    public function destroy(LogSheet $logSheet)
    {
        //
    }
}
