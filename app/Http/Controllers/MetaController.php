<?php

namespace App\Http\Controllers;

use App\Meta;
use App\FileEntry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Dingo\Api\Exception\ResourceException;
use Sunra\PhpSimple\HtmlDomParser;
use Screen\Capture;

class MetaController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function get(Request $request) {
        $rules = [
            'link' => ['required', 'url']
        ];
        $validator = app('validator')->make($request->all(), $rules);
        if ($validator->fails()) {
            throw new ResourceException('Could not fetch metadata.', $validator->errors());
        }

        $link = $request->input('link');

        $meta = Meta::where('link', $link)->first();

        if ($meta) {
            return response()->json($meta);
        }

        $title = 'No title';
        $description = 'No description';
        $dom = HtmlDomParser::file_get_html($link);

        $titleElems = $dom->find('title');
        if (count($titleElems) > 0) {
          $title = $titleElems[0]->plaintext;
        }

        $descriptionElems = $dom->find('meta[name="description"]');
        if (count($descriptionElems) > 0) {
          $description = $descriptionElems[0]->content;
        }

        //need to save first, if we cannot get screenshot
        $newMeta = Meta::create([
          'link' => $link,
          'title' => $title,
          'description' => $description,
          'thumb_id' => 1
        ]);

        //done getting metadata, now get screenshot
        $screenCapture = new Capture($link);

        $screenCapture->setWidth(1200);
        $screenCapture->setHeight(800);
        $screenCapture->setClipWidth(1200);
        $screenCapture->setClipHeight(800);

        $screenCapture->setBackgroundColor('#ffffff');

        $filename = 'screenshot'.getdate()[0].'.jpg';
        $fileLocation = '../storage/app/'.$filename;
        $screenCapture->save($fileLocation);

        //save into file entry table
        $fileEntry = FileEntry::create([
            'filename' => $filename,
            'mime' => 'image/jpeg',
            'original_filename' => $filename
        ]);

        //update meta
        $newMeta->thumb_id = $fileEntry->id;
        $newMeta->save();

        return response()->json($newMeta);
    }

}
