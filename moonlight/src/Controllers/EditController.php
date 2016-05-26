<?php

namespace Moonlight\Controllers;

use Illuminate\Http\Request;
use Moonlight\Main\LoggedUser;
use Moonlight\Main\Element;

class EditController extends Controller
{
    /**
     * Edit element.
     * 
     * @return View
     */
    public function edit(Request $request, $classId)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        $element = Element::getByClassId($classId);
        
        if ( ! $element) {
            return redirect()->route('browse');
        }
        
        $parent = Element::getParent($element);
        
        $currentItem = $element->getItem();
        
        $propertyList = $currentItem->getPropertyList();
        
        $properties = [];
		
        foreach ($propertyList as $propertyName => $property) {
			if ($property->getHidden()) continue;

			$properties[] = $property->setElement($element);
		}

        $scope['element'] = $element;
        $scope['parent'] = $parent;
        $scope['currentItem'] = $currentItem;
        $scope['properties'] = $properties;
        
        return view('moonlight::edit', $scope);
    }
}