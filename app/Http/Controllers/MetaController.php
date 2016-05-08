<?php

namespace App\Http\Controllers;

use App\Meta;
use App\FileEntry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Dingo\Api\Exception\ResourceException;
use Sunra\PhpSimple\HtmlDomParser;
use Screen\Capture;
use Illuminate\Support\Facades\Log;
use finfo;

function get_http_response_code($url) {
    $headers = get_headers($url);
    return substr($headers[0], 9, 3);
}

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

        // $meta = Meta::where('link', $link)->first();
        //
        // if ($meta) {
        //     return response()->json($meta);
        // }

        $title = 'No title';
        $description = 'No description';
        $url = '';
        $filename = '';
        $mime_type = '';

        $dom = HtmlDomParser::str_get_html($link);

        $isActive = get_http_response_code($link);
        //url is not active
        if (get_http_response_code($link) != '200')
            $dom = false;

        if ($dom) {
            $dom->load(file_get_contents($link, false, null, -1));
            $titleElems = $dom->find('title');
            if (count($titleElems) > 0) {
              $title = $titleElems[0]->plaintext;
            }

            $descriptionElems = $dom->find('meta[name="description"]');
            if (count($descriptionElems) > 0) {
              $description = $descriptionElems[0]->content;
            }
            //done getting metadata, now get first img or screenshot if no img found
            $og = $dom->find('meta[property="og:image"]') ? $dom->find('meta[property="og:image"]') : $dom->find('meta[property="og:image:url"]');
            if (count($og) > 0) {
                $url = $og[0]->content;
            }
            else {
                $img = $dom->find('img');
                if (count($img) > 0) {
                    $url = $img[0]->src;
                }
            }

        }

        //need to save first, if we cannot get screenshot
        $newMeta = Meta::create([
          'link' => $link,
          'title' => $title,
          'description' => $description,
          'thumb_id' => 1
        ]);

        if (strpos($url, 'http') !== false) {
        } else {
            $url = $link.$url;
        }

        if ($url != '' && get_http_response_code($url) == '200') {
            $file = file_get_contents($url);
            //get mime type of img
            $file_info = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $file_info->buffer($file);

            //get filename
            $url_arr = explode ('/', $url);
            $name = $url_arr[count($url_arr)-1];
            $name_div = explode('.', $name);
            $filename = '';

            $img_type = $name_div[count($name_div)-1];
            if (count($name_div) == 1)
                $filename = 'screenshot'.getdate()[0];
            else
                $filename = 'screenshot'.getdate()[0].'.'.$img_type;
            $fileLocation = '../storage/app/'.$filename;
            file_put_contents($fileLocation, $file);
        }
        else {
            $screenCapture = new Capture($link);

            $screenCapture->setWidth(1200);
            $screenCapture->setHeight(800);
            $screenCapture->setClipWidth(1200);
            $screenCapture->setClipHeight(800);

            $screenCapture->setBackgroundColor('#ffffff');

            $filename = 'screenshot'.getdate()[0].'.jpg';
            $fileLocation = '../storage/app/'.$filename;
            $screenCapture->save($fileLocation);

            $mime_type = 'image/jpeg';
        }

        //save into file entry table
        $fileEntry = FileEntry::create([
            'filename' => $filename,
            'mime' => $mime_type
        ]);

        //update meta
        $newMeta->thumb_id = $fileEntry->id;
        $newMeta->save();

        return response()->json($newMeta);
    }

}
