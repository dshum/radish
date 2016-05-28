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
        
        $map = [];
        
        foreach ($favorites as $favorite) {
            $map[$favorite->rubric_id] = $favorite;
        }
        
        $scope['favoriteRubrics'] = $favoriteRubrics;
        $scope['favorites'] = $favorites;
        $scope['map'] = $map;
            
        return view('moonlight::home', $scope);
    }
}