<?php

namespace Moonlight\Controllers;

use Illuminate\Http\Request;
use Moonlight\Main\LoggedUser;
use Moonlight\Main\Element;
use Moonlight\Models\FavoriteRubric;
use Moonlight\Models\Favorite;

class HomeController extends Controller
{
    /**
     * Show favorite rubric list for autocomplete.
     *
     * @return Response
     */
    public function favorites(Request $request)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        $classId = $request->input('classId');
        
        $favoriteRubrics = FavoriteRubric::orderBy('order')->get();
        
        $scope['suggestions'] = [];
        
        foreach ($favoriteRubrics as $favoriteRubric) {
            $scope['suggestions'][] = [
                'value' => $favoriteRubric->name,
                'data' => $favoriteRubric->id,
            ];
        }
        
        return response()->json($scope);
    }
    
    /**
     * Add/remove favorite element.
     *
     * @return Response
     */
    public function favorite(Request $request)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        $classId = $request->input('classId');
        $rubricId = $request->input('rubricId');
        $rubric = $request->input('rubric');
        $action = $request->input('action');
        
        if ($action == 'orderRubrics') {
            $rubrics = $request->input('rubric');
            
            if (is_array($rubrics) && sizeof($rubrics) > 1) {
                foreach ($rubrics as $order => $id) {
                    $favoriteRubric = FavoriteRubric::find($id);
                    
                    if ($favoriteRubric) {
                        $favoriteRubric->order = $order;
                        $favoriteRubric->save();
                    }
                }
                
                $scope['ordered'] = $rubrics;
            }
            
            return response()->json($scope);
        }
        
        if ($action == 'order') {
            $favorites = $request->input('favorite');
            
            \Log::info($favorites);
            
            if (is_array($favorites) && sizeof($favorites) > 1) {
                foreach ($favorites as $order => $id) {
                    $favorite = Favorite::find($id);
                    
                    if ($favorite) {
                        $favorite->order = $order;
                        $favorite->save();
                    }
                }
                
                $scope['ordered'] = $favorites;
            }
            
            return response()->json($scope);
        }
        
        if ($action == 'dropRubric' && $rubricId) {
            $favoriteRubric = FavoriteRubric::find($rubricId);
            
            if ($favoriteRubric) {
                $favoriteRubric->delete();
                $scope['deleted'] = $favoriteRubric->id;
            }
            
            return response()->json($scope);
        }
        
        $element = Element::getByClassId($classId);
        
        if ( ! $element) {
            return response()->json(['error' => 'Элемент не найден.']);
        }
        
        $favorite = Favorite::where(
            function($query) use ($loggedUser, $classId) {
                $query->where('user_id', $loggedUser->id);
                $query->where('class_id', $classId);
            }
        )->first();
        
        if ($action == 'drop' && $favorite) {
            $favorite->delete();
            $scope['deleted'] = $favorite->id;
            
            return response()->json($scope);
        }
        
        if ( ! $rubric) {
            return response()->json(['error' => 'Рубрика не указана.']);
        }
        
        $favoriteRubric = FavoriteRubric::where(
            function($query) use ($loggedUser, $rubric) {
                $query->where('user_id', $loggedUser->id);
                $query->where('name', $rubric);
            }
        )->first();
        
        if ( ! $favoriteRubric) {
            $favoriteRubric = new FavoriteRubric;
            
            $favoriteRubric->user_id = $loggedUser->id;
            $favoriteRubric->name = $rubric;
            
            $favoriteRubric->save();
        }

        if ($action == 'add' && ! $favorite) {
            $favorite = new Favorite;
            
            $favorite->user_id = $loggedUser->id;
            $favorite->class_id = $classId;
            $favorite->rubric_id = $favoriteRubric->id;
            
            $favorite->save();
            
            $scope['added'] = $favorite->id;
        }
        
        return response()->json($scope);
    }
    
    /**
     * Show the home.
     *
     * @return View
     */
    public function show(Request $request)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        $favoriteRubrics = FavoriteRubric::where('user_id', $loggedUser->id)->orderBy('order')->get();
        $favorites = Favorite::where('user_id', $loggedUser->id)->orderBy('order')->get();
        
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