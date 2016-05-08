<?php

namespace App\Http\Controllers;

use App\Meta;
use App\FileEntry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Dingo\Api\Exception\ResourceException;
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

        // Request

        $serviceUrl = 'http://128.199.211.29:8989/';
        $data = array('link' => $link);
        $data_string = json_encode($data);

        $ch = curl_init($serviceUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);

        return response()->json($result);
    }

}
