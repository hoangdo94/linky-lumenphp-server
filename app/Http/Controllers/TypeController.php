<?php
  
namespace App\Http\Controllers;
  
use App\Type;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
  
class TypeController extends Controller{
  
  
    public function index(){
  
        $Types  = Type::all();
  
        return response()->json($Types);
  
    }
  
    public function getType($id){
  
        $Type  = Type::find($id);
  
        return response()->json($Type);
    }
  
    public function createType(Request $request){
  
        $Type = Type::create($request->all());
        return response()->json(['status' => '1']);
    }
  
    public function deleteType($id){
        $Type  = Type::find($id);
        $Type->delete();
 
        return response()->json('deleted');
    }
  
    public function updateType(Request $request,$id){
        $Type  = Type::find($id);
        $Type->name = $request->input('name');
        $Type->save();
  
        return response()->json($Type);
    }
  
}