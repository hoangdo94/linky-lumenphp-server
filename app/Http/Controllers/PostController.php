<?php
  
namespace App\Http\Controllers;
  
use App\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
  
class PostController extends Controller{
  
  
    public function index(){
  
        $Posts  = Post::all();
  
        return response()->json($Posts);
  
    }
  
    public function getPost($id){
  
        $Post  = Post::find($id);
  
        return response()->json($Post);
    }

    public function getPostFromUserId(Request $request){
  
        if ($request->has('user_id')) {
            $Post = Post::where('user_id', $request->input('user_id'))->get();
            return response()->json($Post);
        }
  
        return response()->json(Post::leftJoin('type', 'post.type_id', '=', 'type.id')->leftJoin('category','post.cate_id', '=', 'category.id')->leftJoin('user','post.user_id', '=', 'user.id')->select('post.*', 'username', 'category.name AS cate_name', 'type.name AS type_name')->get());
    }

    public function createPost(Request $request){
  
        $Post = Post::create($request->all());
  
        return response()->json($Post);
  
    }
  
    public function deletePost($id){
        $Post  = Post::find($id);
        $Post->delete();
 
        return response()->json('deleted');
    }
  
    public function updatePost(Request $request,$id){
        $Post  = Post::find($id);
        $Post->category_id = $request->input('category_id');
        $Post->type_id = $request->input('type_id');
        $Post->num_likes = $request->input('num_likes');
        $Post->link = $request->input('link');
        $Post->content = $request->input('content');
        $Post->save();
  
        return response()->json($Post);
    }
  
}