<?php

namespace App\Http\Controllers\Admin;

use Storage;
use App\Models\Import;
use App\Imports\DataImport;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Http\Controllers\Controller;

class ImportController extends Controller
{
    public function index(){

        return view('admin.imports.index', [
            'imports' => Import::orderBy('id', 'desc')->paginate(),
        ]);
    }

    public function create(){
        return view('admin.imports.create');
    }

    public function store(Request $request){
        $temporaryFile = TemporaryFile::where('folder', $request->file)->first();

        if($temporaryFile){
            Storage::move('uploads/tmp/' . $request->file . '/' . $temporaryFile->filename , 'imports/' . $request->file . '/' . $temporaryFile->filename);
            $path = 'imports/' . $request->file . '/' . $temporaryFile->filename;
            $import = new DataImport;
            $import->import($path);
            Import::create([
                'file_name' => $path,
            ]);
            Storage::deleteDirectory('uploads/tmp/' . $request->file);
            $temporaryFile->delete();
            return redirect()->route('admin.imports.index')->with('message', 'File Imported Successfully.');
        }else{
            return redirect()->route('admin.imports.create')->withErrors('Temporary File not found.');
        }

    }

    public function download(Request $request){
        if(!$import = Import::find($request->import_id)){
            return redirect()->route('admin.imports.index')->withErrors('Import not found.');
        }

        if(Storage::exists($import->file_name)){
            $uploaded_file_name = basename($import->file_name);
            return Storage::download($import->file_name, 'imported_'. $import->created_at . '_' . $uploaded_file_name);
        }else{
            return redirect()->route('admin.imports.index')->withErrors('File not found.');
        }
    }
}
