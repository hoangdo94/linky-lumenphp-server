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