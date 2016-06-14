<?php

namespace Moonlight\Middleware;

use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Route;
use Closure;
use Session;
use Moonlight\Main\LoggedUser;
use Moonlight\Models\User;

class HistoryMiddleware
{
    public function handle($request, Closure $next)
    {   
        $loggedUser = LoggedUser::getUser();
        
        $history = $loggedUser->getParameter('history');
        
        $history = config('app.url').$request->getRequestUri();
        
        $loggedUser->setParameter('history', $history);

        return $next($request);
    }
}