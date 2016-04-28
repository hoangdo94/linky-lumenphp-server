<?php

namespace App\Http\Controllers;

use App\Type;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class TypesController extends Controller {

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
        $Types  = Type::all();
        return response()->json($Types);
    }

    public function get($id) {
        $Type  = Type::findOrFail($id);
        return response()->json($Type);
    }

    public function create(Request $request) {
        $Type = Type::create($request->all());
        return response()->json([
            'message' => 'Created type',
            'status_code' => '200',
            'data' => $Type
        ]);
    }

    public function delete($id) {
        $Type  = Type::findOrFail($id);
        $Type->delete();
        return response()->json([
            'message' => 'Deleted type',
            'status_code' => '200',
            'data' => $Type
        ]);
    }

    public function update(Request $request,$id) {
        $Type  = Type::findOrFail($id);
        $Type->name = $request->input('name');
        $Type->save();
        return response()->json([
            'message' => 'Created type',
            'status_code' => '200',
            'data' => $Type
        ]);
    }

}
