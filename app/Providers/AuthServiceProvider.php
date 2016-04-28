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

        Gate::define('modify-user', function ($u1, $u2) {
            return ($u1->isAdmin || $u1->id === $u2->id);
        });

        Gate::define('modify-post', function ($u, $p) {
            return ($u->isAdmin || $u->id === $p->user_id);
        });

        Gate::define('do-restricted-actions', function ($u) {
            return ($u->isAdmin);
        });
    }
}
