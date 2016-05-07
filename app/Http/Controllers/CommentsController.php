<?php

namespace App\Http\Controllers;

use Auth;
use App\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Dingo\Api\Exception\StoreResourceFailedException;

class CommentsController extends Controller {

    public function __construct() {
        $this->middleware('auth', [
            'only' => [
                'create',
                'delete',
                'update'
            ]
        ]);
    }

    /*
        return all comments of a Post
    */
    public function index() {
        if (Request::has('id')) {
            $Comment = Comment::where('post_id', '=', Request::input('id'))->leftJoin('user', 'comment.user_id', '=', 'user.id')->select('comment.*', 'user.username', 'user.avatar_id')->get();
            return response()->json($Comment);    
        }
    }

    public function get($id) {
        $Comment  = Comment::findOrFail($id);
        return response()->json($Comment);
    }

    public function create() {
        $rules = [
            'post_id' => ['required', 'exists:post,id'],
            'content' => ['required']
        ];
        $validator = app('validator')->make(Request::all(), $rules);
        if ($validator->fails()) {
            throw new StoreResourceFailedException('Could not create new Comment.', $validator->errors());
        }
        $user = Auth::user();
        $Comment = Comment::create([
            'user_id' => $user->id,
            'post_id' => Request::input('post_id'),
            'content' => Request::input('content')
        ]);
        return response()->json([
            'message' => 'Created Comment',
            'status_code' => '200',
            'data' => $Comment
        ]);
    }

    public function delete($id) {
        $user = Auth::user();
        $Comment  = Comment::findOrFail($id);
        if ($user->id != $Comment->user_id) {
            throw new AccessDeniedHttpException('No permission');
        }
        $Comment->delete();
        return response()->json([
            'message' => 'Deleted Comment',
            'status_code' => '200',
            'data' => $Comment
        ]);
    }

    public function update($id) {
        $user = Auth::user();
        $Comment  = Comment::findOrFail($id);
        if ($user->id != $Comment->user_id) {
            throw new AccessDeniedHttpException('No permission');
        }
        if (Request::has('post_id')) {
            $Comment->cate_id = Request::input('post_id');
        }
        if (Request::has('content')) {
            $Comment->content = Request::input('content');
        }
        $Comment->save();
        return response()->json([
            'message' => 'Updated Comment',
            'status_code' => '200',
            'data' => $Comment
        ]);
    }

}
