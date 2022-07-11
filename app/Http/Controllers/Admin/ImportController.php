<?php

namespace App\Http\Controllers\Admin;

use Storage;
use App\Models\Import;
use App\Models\Location;
use App\Imports\DataImport;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Http\Controllers\Controller;

class ImportController extends Controller
{
    public function index()
    {
        $imports = null;
        if (auth()->user()->hasRole('admin')) {
            $imports = Import::orderBy('id', 'desc')->with('location')->paginate();
        } else if(auth()->user()->hasRole('manager')) {
            $location_ids = auth()->user()->manager->locations->pluck('id')->toArray();
            $imports = Import::whereIn('location_id', $location_ids)->orderBy('id', 'desc')->with('location')->paginate();
        }
        return view('admin.imports.index', [
            'imports' => $imports,
        ]);
    }

    public function create()
    {
        if (!auth()->user()->hasRole('manager')) {
            return redirect()->route('admin.imports.index');
        }
        $manager = auth()->user()->manager;
        return view('admin.imports.create', [
            'locations' => $manager->locations,
        ]);
    }

    public function store(Request $request)
    {
        $temporaryFile = TemporaryFile::where('folder', $request->file)->first();
        $location_id = null;

        if (auth()->user()->hasRole('manager')) {
            $location_id = $request->location_id;
        } else {
            return redirect()->back()->with('error', 'You are not authorized to perform this action.');
        }

        if ($temporaryFile) {
            Storage::move('uploads/tmp/' . $request->file . '/' . $temporaryFile->filename, 'imports/' . $request->file . '/' . $temporaryFile->filename);
            $path = 'imports/' . $request->file . '/' . $temporaryFile->filename;
            $import_file = new DataImport($location_id);
            $import_file->import($path);
            $import_model = Import::create([
                'file_name' => $path,
            ]);
            $import_model->update([
                'location_id' => $location_id,
            ]);
            Storage::deleteDirectory('uploads/tmp/' . $request->file);
            $temporaryFile->delete();
            return redirect()->route('admin.imports.index')->with('message', 'File Imported Successfully.');
        } else {
            return redirect()->route('admin.imports.create')->withErrors('Temporary File not found.');
        }
    }

    public function download(Request $request)
    {
        if (!$import = Import::find($request->import_id)) {
            return redirect()->route('admin.imports.index')->withErrors('Import not found.');
        }

        if (Storage::exists($import->file_name)) {
            $uploaded_file_name = basename($import->file_name);
            return Storage::download($import->file_name, 'imported_' . $import->created_at . '_' . $uploaded_file_name);
        } else {
            return redirect()->route('admin.imports.index')->withErrors('File not found.');
        }
    }
}
