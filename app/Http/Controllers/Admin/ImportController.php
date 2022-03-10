<?php

namespace App\Http\Controllers\Admin;

use App\Imports\DataImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImportController extends Controller
{
    public function index(){
        return view('admin.imports.index');
    }

    public function create(){
        return view('admin.imports.create');
    }

    public function store(Request $request){
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx,csv,ods|max:2048',
        ]);
        $path = $request->file('file')->store('imports');

        $import = new DataImport;
        $import->import($path);

        return redirect()->route('admin.imports.index')->with('message', 'File Uploaded Successfully.');
    }
}
