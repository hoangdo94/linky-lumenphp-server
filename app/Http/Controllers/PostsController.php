<?php

namespace App\Http\Controllers;

use Auth;
use App\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $Posts  = Post::all();
        return response()->json($Posts);
    }

    public function get($id) {
        $Post  = Post::findOrFail($id);
        return response()->json($Post);
    }

    public function getPostFromUserId(Request $request) {
        if ($request->has('user_id')) {
            $Post = Post::where('user_id', $request->input('user_id'))->get();
            return response()->json($Post);
        }
        return response()->json(Post::leftJoin('type', 'post.type_id', '=', 'type.id')->leftJoin('category','post.cate_id', '=', 'category.id')->leftJoin('user','post.user_id', '=', 'user.id')->select('post.*', 'username', 'category.name AS cate_name', 'type.name AS type_name')->get());
    }

    public function create(Request $request) {
        $rules = [
            'cate_id' => ['required', 'exists:category,id'],
            'type_id' => ['required', 'exists:type,id'],
            'thumb_id' => ['required', 'exists:file_entry,id'],
            'link' => ['required'],
            'content' => ['required']
        ];
        $validator = app('validator')->make($request->all(), $rules);
        if ($validator->fails()) {
            throw new StoreResourceFailedException('Could not create new post.', $validator->errors());
        }
        $user = Auth::user();
        $Post = Post::create([
            'user_id' => $user->id,
            'cate_id' => $request->input('cate_id'),
            'type_id' => $request->input('type_id'),
            'thumb_id' => $request->input('thumb_id'),
            'link' => $request->input('link'),
            'content' => $request->input('content')
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

    public function update(Request $request,$id) {
        $user = Auth::user();
        $Post  = Post::findOrFail($id);
        if ($user->cannot('modify-post')) {
            throw new AccessDeniedHttpException('No permission');
        }
        if ($request->has('cate_id')) {
            $Post->cate_id = $request->input('cate_id');
        }
        if ($request->has('type_id')) {
            $Post->type_id = $request->input('type_id');
        }
        if ($request->has('thumb_id')) {
            $Post->type_id = $request->input('thumb_id');
        }
        if ($request->has('link')) {
            $Post->link = $request->input('link');
        }
        if ($request->has('content')) {
            $Post->content = $request->input('content');
        }
        $Post->save();
        return response()->json([
            'message' => 'Updated post',
            'status_code' => '200',
            'data' => $Post
        ]);
    }

}
