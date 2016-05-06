<?php

namespace App\Http\Controllers;

use Auth;
use App\Follow;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
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

            // Get followers/following of 'someone' user
            // Not ony current user, FUCK OFF!
            if (Request::has('user_id')) {
                $user_id = Request::input('user_id');

                /*
                    type = 1: get follower list of user
                    type = 2: get following list of user
                */
                if ($type == '1' && $user_id != $user->id){
                    $Follow = Follow::where('user_id', $user_id)->leftJoin('user', 'follow.follower_id', '=', 'user.id')->select('user.*')->get();
                    return response()->json($Follow);
                } 
                else if ($type == '2' && $user_id != $user->id) {
                    $Follow = Follow::where('follower_id', $user_id)->leftJoin('user', 'follow.user_id', '=', 'user.id')->select('user.*')->get();
                    return response()->json($Follow);
                }
            }
            /*
                type = 1: get follower list of user
                type = 2: get following list of user
            */
            if ($type == '1'){
                $Follow = Follow::where('user_id', $user->id)->leftJoin('user', 'follow.follower_id', '=', 'user.id')->select('user.*')->get();
                return response()->json($Follow);
            }
            else {
                $Follow = Follow::where('follower_id', $user->id)->leftJoin('user', 'follow.user_id', '=', 'user.id')->select('user.*')->get();
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
        if ($user->id == (int) Request::input('id'))
            return response()->json([
                'message' => 'Cannot follow yourself'
            ]);
        if (Follow::where('user_id', '=', Request::input('id'))->where('follower_id', '=', $user->id)->count() == 1)
            return response()->json([
                'message' => 'already followed this user'
            ]);
        $Follow = Follow::create([
            'user_id' => Request::input('id'),
            'follower_id' => $user->id
        ]);
        // update num follow
        $user->num_followings = $user->num_followings + 1;
        $user->save();

        $another = User::find(Request::input('id'));
        $another->num_followers = $another->num_followers + 1;
        $another->save();

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
        $user = Auth::user();
        $Follow = Follow::where('user_id', $id)->where('follower_id', $user->id)->delete();

        // update num follow
        $user->num_followings = $user->num_followings - 1;
        $user->save();

        $another = User::find($id);
        $another->num_followers = $another->num_followers - 1;
        $another->save();

        return response()->json([
            'message' => 'Deleted Follow',
            'status_code' => '200',
            'data' => $Follow
        ]);
    }

}
