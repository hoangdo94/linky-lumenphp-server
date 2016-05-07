<?php

namespace App\Http\Controllers;

use Auth;
use App\PreferCategory;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Dingo\Api\Exception\StoreResourceFailedException;

class PreferCategoriesController extends Controller {

    public function __construct() {
        $this->middleware('auth', [
            'only' => [
                'index',
                'create',
                'delete'
            ]
        ]);
    }

    /*
        return list of categories that user prefer
    */
    public function index() {
        $user = Auth::user();
        return response()->json(PreferCategory::where('user_id', '=', $user->id)->leftJoin('category', 'prefer_category.cate_id', '=', 'category.id')->select('prefer_category.*', 'category.name')->get());
    }

    /*
        create new PreferCategory from list of cate_ids
    */
    public function create() {
        $user = Auth::user();
        //delete old prefer_categories
        $prefer_categories = PreferCategory::where('user_id', $user->id);
        $prefer_categories->delete();

        $cates = explode(',', Request::input('categories'));

        for ($i=0; $i < count($cates); $i++) {
            $cate = Category::where('name', $cates[$i])->firstOrFail();
            PreferCategory::create([
                'user_id' => $user->id,
                'cate_id' => $cate->id
            ]);
        }
        
        return response()->json([
            'message' => 'Created PreferCategory',
            'status_code' => '200'
        ]);
    }

    /*
        delete PreferCategory
    */
    public function delete($id) {
        $user = Auth::user();
        //delete PreferCategory
        $PreferCategory = PreferCategory::where('user_id', '=', $user->id)->where('cate_id', '=', $id)->delete();
        
        return response()->json([
            'message' => 'Deleted PreferCategory',
            'status_code' => '200',
            'data' => $PreferCategory
        ]);
    }

}
