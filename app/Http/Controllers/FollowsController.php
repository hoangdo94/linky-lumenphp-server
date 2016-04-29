<?php

namespace App\Http\Controllers;

use Auth;
use App\Follow;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Dingo\Api\Exception\StoreResourceFailedException;

class FollowsController extends Controller {

    public function __construct() {
        $this->middleware('auth', [
            'only' => [
                'create',
                'delete',
                'index'
            ]
        ]);
    }

    public function index() {
        $user = Auth::user();
        if (Request::has('type')) {
            $type = Request::input('type');
            /*
                type = 1: get follower list of user
                type = 2: get following list of user
            */
            if ($type == '1'){
                $Follow = Follow::where('user_id', $user->id)->leftJoin('user', 'follow.follower_id', '=', 'user.id')->select('user.username')->get();
                return response()->json($Follow);
            }
            else {
                $Follow = Follow::where('follower_id', $user->id)->leftJoin('user', 'follow.user_id', '=', 'user.id')->select('user.username')->get();
                return response()->json($Follow);
            }
        }
        $Follow  = Follow::all();
        return response()->json($Follow);
    }

    public function create() {
        /*
            Apply only for creating following user
        */
        $validator = app('validator')->make(Request::all(), ['id' => ['required', 'exists:user,id']]);
        if ($validator->fails()) {
            throw new StoreResourceFailedException('Could not create new follow.', $validator->errors());
        }
        $user = Auth::user();
        $Follow = Follow::create([
            'user_id' => Request::input('id'),
            'follower_id' => $user->id
        ]);
        return response()->json([
            'message' => 'Created Follow',
            'status_code' => '200',
            'data' => $Follow
        ]);
    }

    public function delete($id) {
        /*
            Apply only for deleting following user
        */
        $Follow = Follow::where('user_id', $id)->where('follower_id', Auth::user()->id)->delete();
        return response()->json([
            'message' => 'Deleted Follow',
            'status_code' => '200',
            'data' => $Follow
        ]);
    }

}
