<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;
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
        $Users = User::all();
        return response()->json($Users);
    }

    public function get($id) {
        $User = User::findOrFail($id);
        return response()->json($User);
    }

    public function create(Request $request) {
        $rules = [
            'username' => ['required', 'unique:user'],
            'email' => ['required', 'email', 'unique:user'],
            'password' => ['required', 'min:6']
        ];
        $validator = app('validator')->make($request->all(), $rules);
        if ($validator->fails()) {
            throw new StoreResourceFailedException('Could not create new user.', $validator->errors());
        }
        $User = User::create($request->all());
        $User->password = app('hash')->make($request->input('password'));
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

    public function update(Request $request, $id) {
        $authUser = Auth::user();
        $User  = User::findOrFail($id);
        if ($authUser->cannot('modify-user', $User)) {
            throw new AccessDeniedHttpException('No permission');
        }

        if ($request->has('password')) {
            $User->password = app('hash')->make($request->input('password'));
        }
        if ($request->has('website')) {
            $User->website = $request->input('website');
        }
        if ($request->has('title')) {
            $User->title = $request->input('title');
        }
        if ($request->has('phone')) {
            $User->phone = $request->input('phone');
        }
        $User->save();
        return response()->json([
            'message' => 'Updated user',
            'status_code' => '200',
            'data' => $User
        ]);
    }

}
