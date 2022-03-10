<?php

namespace App\Http\Controllers\Admin;

use App\Imports\DataImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Import;
use Storage;

class ImportController extends Controller
{
    public function index(){

        return view('admin.imports.index', [
            'imports' => Import::paginate(),
        ]);
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

        Import::create([
            'file_name' => $path,
        ]);

        return redirect()->route('admin.imports.index')->with('message', 'File Uploaded Successfully.');
    }

    public function download(Request $request){
        if(!$import = Import::find($request->import_id)){
            return redirect()->route('admin.imports.index')->withErrors('Import not found.');
        }

        if(Storage::exists($import->file_name)){
            return Storage::download($import->file_name, 'imported_'. $import->created_at .'.xlsx');
        }else{
            return redirect()->route('admin.imports.index')->withErrors('File not found.');
        }
    }
}
