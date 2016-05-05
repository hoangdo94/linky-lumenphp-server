<?php

namespace App\Http\Controllers;

use Auth;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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

    public function create() {
        $user = Auth::user();
        if ($user->isAdmin != 1)
            throw new AccessDeniedHttpException('No permission');
        $Category = Category::create(Request::all());
        return response()->json([
            'message' => 'Created category',
            'status_code' => '200',
            'data' => $Category
        ]);
    }

    public function delete($id) {
        $user = Auth::user();
        if ($user->isAdmin != 1)
            throw new AccessDeniedHttpException('No permission');
        $Category  = Category::findOrFail($id);
        $Category->delete();
        return response()->json([
            'message' => 'Deleted category',
            'status_code' => '200',
            'data' => $Category
        ]);
    }

    public function update($id) {
        $user = Auth::user();
        if ($user->isAdmin != 1)
            throw new AccessDeniedHttpException('No permission');
        $Category  = Category::findOrFail($id);
        $Category->name = Request::input('name');
        $Category->save();
        return response()->json([
            'message' => 'Updated category',
            'status_code' => '200',
            'data' => $Category
        ]);
    }

}
