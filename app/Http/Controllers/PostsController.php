<?php

namespace App\Http\Controllers;

use Auth;
use App\Post;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Dingo\Api\Exception\StoreResourceFailedException;

class PostsController extends Controller {

    public function __construct() {
        $this->middleware('auth', [
            'only' => [
                'create',
                'delete',
                'update'
            ]
        ]);
    }

    public function index() {
        if (Request::has('user_id')) {
            $Post = Post::where('user_id', Request::input('user_id'))->leftJoin('type', 'post.type_id', '=', 'type.id')->leftJoin('category','post.cate_id', '=', 'category.id')->leftJoin('user','post.user_id', '=', 'user.id')->orderBy('created_at', 'desc')->select('post.*', 'username', 'category.name AS cate_name', 'type.name AS type_name')->get();
            return response()->json($Post);
        }
        return response()->json(Post::leftJoin('type', 'post.type_id', '=', 'type.id')->leftJoin('category','post.cate_id', '=', 'category.id')->leftJoin('user','post.user_id', '=', 'user.id')->orderBy('created_at', 'desc')->select('post.*', 'username', 'category.name AS cate_name', 'type.name AS type_name')->get());
    }

    public function get($id) {
        $Post  = Post::findOrFail($id);
        return response()->json($Post);
    }

    public function create() {
        $rules = [
            'cate_id' => ['required', 'exists:category,id'],
            'type_id' => ['required', 'exists:type,id'],
            'meta_id' => ['required', 'exists:meta,id'],
            'content' => ['required']
        ];
        $validator = app('validator')->make(Request::all(), $rules);
        if ($validator->fails()) {
            throw new StoreResourceFailedException('Could not create new post.', $validator->errors());
        }
        $user = Auth::user();
        $Post = Post::create([
            'user_id' => $user->id,
            'cate_id' => Request::input('cate_id'),
            'type_id' => Request::input('type_id'),
            'meta_id' => Request::input('meta_id'),
            'content' => Request::input('content')
        ]);
        return response()->json([
            'message' => 'Created post',
            'status_code' => '200',
            'data' => $Post
        ]);
    }

    public function delete($id) {
        $user = Auth::user();
        $Post  = Post::findOrFail($id);
        if ($user->cannot('modify-post')) {
            throw new AccessDeniedHttpException('No permission');
        }
        $Post->delete();
        return response()->json([
            'message' => 'Deleted post',
            'status_code' => '200',
            'data' => $Post
        ]);
    }

    public function update($id) {
        $user = Auth::user();
        $Post  = Post::findOrFail($id);
        if ($user->cannot('modify-post')) {
            throw new AccessDeniedHttpException('No permission');
        }
        if (Request::has('cate_id')) {
            $Post->cate_id = Request::input('cate_id');
        }
        if (Request::has('type_id')) {
            $Post->type_id = Request::input('type_id');
        }
        if (Request::has('thumb_id')) {
            $Post->type_id = Request::input('thumb_id');
        }
        if (Request::has('link')) {
            $Post->link = Request::input('link');
        }
        if (Request::has('content')) {
            $Post->content = Request::input('content');
        }
        $Post->save();
        return response()->json([
            'message' => 'Updated post',
            'status_code' => '200',
            'data' => $Post
        ]);
    }

}
