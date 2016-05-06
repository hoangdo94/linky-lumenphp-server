<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use App\User;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Dingo\Api\Exception\StoreResourceFailedException;

class UsersController extends Controller {

    public function __construct() {
        $this->middleware('auth', [
            'only' => [
                'delete',
                'update'
            ]
        ]);
    }

    public function index() {
        $Users = User::paginate(Request::has('perPage')? Request::input('perPage') : 10);
        return response()->json($Users);
    }

    public function get($id) {
        $User = User::findOrFail($id);
        return response()->json($User);
    }

    public function create() {
        $rules = [
            'username' => ['required', 'unique:user'],
            'email' => ['required', 'email', 'unique:user'],
            'password' => ['required', 'min:6'],
            'website' => ['url']
        ];
        $validator = app('validator')->make(Request::all(), $rules);
        if ($validator->fails()) {
            throw new StoreResourceFailedException('Could not create new user.', $validator->errors());
        }
        $User = User::create(Request::all());
        $User->password = app('hash')->make(Request::input('password'));
        $User->save();
        return response()->json([
            'message' => 'Created user',
            'status_code' => '200',
            'data' => $User
        ]);
    }

    public function delete($id) {
        $authUser = Auth::user();
        $User  = User::findOrFail($id);
        if ($authUser->cannot('modify-user', $User)) {
            throw new AccessDeniedHttpException('No permission');
        }
        $User->delete();
        return response()->json([
            'message' => 'Deleted user',
            'status_code' => '200',
            'data' => $User
        ]);
    }

    public function update($id) {
        $authUser = Auth::user();
        $User  = User::findOrFail($id);
        if ($authUser->cannot('modify-user', $User)) {
            throw new AccessDeniedHttpException('No permission');
        }

        if (Request::has('avatar_id')) {
            $User->avatar_id = Request::input('avatar_id');
        }
        if (Request::has('cover_id')) {
            $User->cover_id = Request::input('cover_id');
        }
        if (Request::has('password')) {
            $User->password = app('hash')->make(Request::input('password'));
        }
        if (Request::has('website')) {
            $User->website = Request::input('website');
        }
        if (Request::has('title')) {
            $User->title = Request::input('title');
        }
        if (Request::has('phone')) {
            $User->phone = Request::input('phone');
        }
        $User->save();
        return response()->json([
            'message' => 'Updated user',
            'status_code' => '200',
            'data' => $User
        ]);
    }

}
