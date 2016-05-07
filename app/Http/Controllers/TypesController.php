<?php

namespace App\Http\Controllers;

use Auth;
use App\Type;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;


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
        if (!Request::has('perPage') && !Request::has('page')) {
            $Types  = Type::all();
            return response()->json($Types);
        }
        else {
            $Types = Type::paginate(Request::has('perPage')? Request::input('perPage') : 10);
            if (Request::has('perPage')) {
                $Types->appends(array('perPage' => Request::input('perPage')))->links();
            }
            return response()->json($Types);
        }
    }

    public function get($id) {
        $Type  = Type::findOrFail($id);
        return response()->json($Type);
    }

    public function create() {
        $user = Auth::user();
        if ($user->isAdmin != 1)
            throw new AccessDeniedHttpException('No permission');
        $Type = Type::create(Request::all());
        return response()->json([
            'message' => 'Created type',
            'status_code' => '200',
            'data' => $Type
        ]);
    }

    public function delete($id) {
        $user = Auth::user();
        if ($user->isAdmin != 1)
            throw new AccessDeniedHttpException('No permission');
        $Type  = Type::findOrFail($id);
        $Type->delete();
        return response()->json([
            'message' => 'Deleted type',
            'status_code' => '200',
            'data' => $Type
        ]);
    }

    public function update($id) {
        $user = Auth::user();
        if ($user->isAdmin != 1)
            throw new AccessDeniedHttpException('No permission');
        $Type  = Type::findOrFail($id);
        $Type->name = Request::input('name');
        $Type->save();
        return response()->json([
            'message' => 'Created type',
            'status_code' => '200',
            'data' => $Type
        ]);
    }

}
