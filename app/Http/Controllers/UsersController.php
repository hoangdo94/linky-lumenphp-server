<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index() {
      $users = User::all();
      return response()->json($users);
    }

    public function get($id) {
      $user = User::find($id);
      return response()->json($user);
    }

    public function create(Request $request) {
      $user = User::create($request->all());
      return response()->json($user);
    }

    public function delete($id) {
      $user = User::find($id);
      $user->delete();
      return response()->json('Deleted');
    }

    public function update(Request $request, $id) {
      $user  = user::find($id);
      $user->save();
      return response()->json('Updated');
    }
}
