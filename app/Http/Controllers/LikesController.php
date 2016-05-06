<?php

namespace App\Http\Controllers;

use Auth;
use App\Like;
use App\Post;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Dingo\Api\Exception\StoreResourceFailedException;

class LikesController extends Controller {

    public function __construct() {
        $this->middleware('auth', [
            'only' => [
                'index',
                'get',
                'create',
                'delete'
            ]
        ]);
    }

    /*
        return who like the post (post_id)
        or total like
    */
    public function index() {
        if (Request::has('id')) {
            // validate post id
            $validator = app('validator')->make(Request::all(), ['id' => ['required', 'exists:post,id']]);
            if ($validator->fails()) {
                throw new StoreResourceFailedException('Post_id not found.', $validator->errors());
            }
            $user = Auth::user();
            return response()->json(Like::where('user_id', '=', $user->id)->where('post_id', '=', Request::input('id'))->leftJoin('user', 'like.user_id', '=', 'user.id')->select('like.*', 'user.username')->get());
        }

        $Like  = Like::all();
        return response()->json($Like);
    }

    /* 
        check if user like the post
        return 1 if liked, 0 vv.
    */
    public function get($id) {
        $user = Auth::user();
        $Like = Like::where('user_id', '=', $user->id)->where('post_id', '=', $id)->count();
        return response()->json(['status' => $Like]);
    }

    /*
        create new like also plus num_likes in the post (post_id) by 1 
    */
    public function create() {
        $validator = app('validator')->make(Request::all(), ['id' => ['required', 'exists:post,id']]);
        if ($validator->fails()) {
            throw new StoreResourceFailedException('Post_id not found.', $validator->errors());
        }
        $user = Auth::user();
        //check exist
        if (Like::where('user_id', '=', $user->id)->where('post_id', '=', Request::input('id'))->count() == 1)
            return response()->json([
                'message' => 'already liked this post'
            ]);
        //create new like
        $Like = Like::create([
            'user_id' => $user->id,
            'post_id' => Request::input('id')
        ]);
        //plus numlike in post by 1
        $Post = Post::find(Request::input('id'));
        $Post->num_likes = $Post->num_likes + 1;
        $Post->save();
        return response()->json([
            'message' => 'Created Like',
            'status_code' => '200',
            'data' => $Like
        ]);
    }

    /*
        delete like also minus num_likes in the post (post_id) by 1 
    */
    public function delete($id) {
        $user = Auth::user();
        //delete like
        $Like = Like::where('user_id', '=', $user->id)->where('post_id', '=', $id)->delete();
        //minus numlike in post by 1
        $Post = Post::find($id);
        $Post->num_likes = $Post->num_likes - 1;
        $Post->save();
        return response()->json([
            'message' => 'Deleted Like',
            'status_code' => '200',
            'data' => $Like
        ]);
    }

}
