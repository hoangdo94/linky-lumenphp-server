<?php namespace App\Http\Controllers;
 
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
 
use Illuminate\Support\Facades\Request;
use App\FileEntry;
class FileEntryController extends BaseController
{
    public function saveFile()
    {
        $file = Request::file('file');
        $extension = $file->getClientOriginalExtension();
        Storage::disk('local')->put($file->getFilename().'.'.$extension,  File::get($file));
        $entry = new Fileentry();
        $entry->mime = $file->getClientMimeType();
        $entry->original_filename = $file->getClientOriginalName();
        $entry->filename = $file->getFilename().'.'.$extension;
 
        $entry->save();
        return response()->json(['status' => '1', 'id' => $entry->id]);
    }
 
    public function deleteFile($id)
    {
        $entry = Fileentry::find($id);
        Storage::delete($entry->filename);
        return response()->json('success');
    }
 
    public function getFile($id) {
        $entry = Fileentry::find($id);
        $file = Storage::disk('local')->get($entry->filename);
 
        return response()->make($file, 200, [
            'Content-Type' => $entry->mime
        ]);
    }
}