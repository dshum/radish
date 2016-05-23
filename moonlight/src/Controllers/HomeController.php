<?php

namespace Moonlight\Controllers;

use Illuminate\Http\Request;
use Moonlight\Main\LoggedUser;
use Moonlight\Models\FavoriteRubric;
use Moonlight\Models\Favorite;

class HomeController extends Controller
{
    /**
     * Show the home.
     *
     * @return View
     */
    public function show(Request $request)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        $favoriteRubrics = FavoriteRubric::where('user_id', $loggedUser->id)->orderBy('id')->get();
        $favorites = Favorite::where('user_id', $loggedUser->id)->orderBy('id')->get();
        
        $scope['favoriteRubrics'] = $favoriteRubrics;
        $scope['favorites'] = $favorites;
            
        return view('moonlight::home', $scope);
    }
}