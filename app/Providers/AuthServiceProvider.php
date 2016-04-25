<?php

namespace App\Providers;

use DB;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        Auth::viaRequest('api', function ($request) {
            if ($request->header('Authorization')) {
                $authHeader = trim(str_replace('Basic ', '', $request->header('Authorization')));
                list($username, $password) = explode(':', base64_decode($authHeader));
                $user = User::where('username', $username)->first();
                if ($user) {
                  $hashedPwd = DB::table('user')->where('username', $username)->first()->password;
                  if (app('hash')->check($password, $hashedPwd)) {
                    return $user;
                  }
                }
            }
        });
    }
}
