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
        return view('admin.log-sheets.index');
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
            'logSheet' => $logSheet->load('location'),
            'invoices' => $logSheet->invoices()->with('clientUser', 'client')->paginate(),
        ]);
    }
}
