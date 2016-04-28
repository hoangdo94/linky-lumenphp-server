<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class CategoriesController extends Controller {

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
        $Categories  = Category::all();
        return response()->json($Categories);
    }

    public function get($id) {
        $Category  = Category::findOrFail($id);
        return response()->json($Category);
    }

    public function create(Request $request) {
        $Category = Category::create($request->all());
        return response()->json([
            'message' => 'Created category',
            'status_code' => '200',
            'data' => $Category
        ]);
    }

    public function delete($id) {
        $Category  = Category::findOrFail($id);
        $Category->delete();
        return response()->json([
            'message' => 'Deleted category',
            'status_code' => '200',
            'data' => $Category
        ]);
    }

    public function update(Request $request,$id) {
        $Category  = Category::findOrFail($id);
        $Category->name = $request->input('name');
        $Category->save();
        return response()->json([
            'message' => 'Updated category',
            'status_code' => '200',
            'data' => $Category
        ]);
    }

}
