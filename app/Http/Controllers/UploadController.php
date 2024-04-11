<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'bail|nullable|file|mimes:xls,xlsx,csv,ods|max:5120',
            '*.image' => 'bail|nullable|file|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $file = $request->file('file') ? $request->file('file') : $request->file('image')[0];
            $filename = $file->getClientOriginalName();
            $folder = uniqid() . '_' . now()->timestamp;
            $file->storeAs('uploads/tmp/' . $folder,  $filename);

            TemporaryFile::create([
                'folder' => $folder,
                'filename' => $filename,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'File upload failed!'], 400);
        }


        return $folder;
    }

    public function destroy(Request $request)
    {
        $temporaryFile = TemporaryFile::where('folder', $request->getContent())->first();

        if($temporaryFile){
            Storage::deleteDirectory('uploads/tmp/' . $request->getContent());
            $temporaryFile->delete();
        }

        return response('', 204);
    }
}
