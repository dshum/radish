<?php

namespace Moonlight\Middleware;

use Log;
use Closure;
use Session;
use Moonlight\Main\LoggedUser;
use Moonlight\Models\User;

class AuthMiddleware
{
    public function handle($request, Closure $next)
    {   
        if ( ! Session::get('logged')) {
            return redirect()->route('login');
        }
        
        $id = Session::get('logged');
        
        $user = User::find($id);
        
        if ( ! $user) {
            return redirect()->route('login');
        }
        
        LoggedUser::setUser($user);
        
        view()->share(['loggedUser' => $user]);

        return $next($request);
    }
}