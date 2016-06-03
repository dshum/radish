<?php

namespace Moonlight\Controllers;

use Illuminate\Http\Request;
use Moonlight\Main\LoggedUser;
use Moonlight\Main\Element;

class SearchController extends Controller
{
    public function item(Request $request, $class)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        $site = \App::make('site');
        
        $currentItem = $site->getItemByName($class);
        
        if ( ! $currentItem) {
            return redirect()->route('search');
        }
        
        $propertyList = $currentItem->getPropertyList();
        
        $properties = [];
		
        foreach ($propertyList as $propertyName => $property) {
			if ($property->getHidden()) continue;

			$properties[] = $property->setRequest($request);
		}
        
        $scope['currentItem'] = $currentItem;
        $scope['properties'] = $properties;
            
        return view('moonlight::searchItem', $scope);
    }
    
    public function index(Request $request)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        $site = \App::make('site');
        
        $items = $site->getItemList();

		$scope['items'] = $items;
            
        return view('moonlight::search', $scope);
    }
}