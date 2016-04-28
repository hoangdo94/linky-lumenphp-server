<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Request;
use App\FileEntry;

class FileEntriesController extends BaseController {

    public function __construct() {
        $this->middleware('auth', [
            'only' => [
                'upload',
                'delete'
            ]
        ]);
    }

    public function upload() {
        $file = Request::file('file');
        $extension = $file->getClientOriginalExtension();
        Storage::disk('local')->put($file->getFilename().'.'.$extension,  File::get($file));
        $entry = new Fileentry();
        $entry->mime = $file->getClientMimeType();
        $entry->original_filename = $file->getClientOriginalName();
        $entry->filename = $file->getFilename().'.'.$extension;
        $entry->save();
        return response()->json([
            'message' => 'Created file entry',
            'status_code' => '200',
            'data' => $entry
        ]);
    }

    public function delete($id) {
        $entry = Fileentry::findOrFail($id);
        Storage::delete($entry->filename);
        $entry->delete();
        return response()->json([
            'message' => 'Deleted file entry',
            'status_code' => '200',
            'data' => $entry
        ]);
    }

    public function get($id) {
        $entry = Fileentry::findOrFail($id);
        $file = Storage::disk('local')->get($entry->filename);
        return response()->make($file, 200, [
            'Content-Type' => $entry->mime
        ]);
    }

}
