<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class AuthController extends Controller{
    public function authenticate(){
        $user = Auth::user();
        return response()->json($user);
    }
}
