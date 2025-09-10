<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;

class FileController extends Controller
{
    // Upload a file
    public function upload(Request $request)
    {

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Store file
            $path = $file->store('uploads', 'public');

            return response()->json([
                'success'   => true,
                'filename'  => $file->getClientOriginalName(),        // <-- return only file name
                'stored_as' => basename($path),                // <-- random name in storage
                'path'      => $path,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
    }

    // Download a file
    public function download($filename)
    {
        $path = 'uploads/' . $filename;
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->download($path, $filename);
        }

        return response()->json(['error' => 'File not found'], 404);
    }
    public function AppDownload()
    {
         $path = 'update/HRIS.exe';
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->download($path, 'HRIS.exe');
        }

        return response()->json(['error' => 'File not found'], 404);
    }

}
