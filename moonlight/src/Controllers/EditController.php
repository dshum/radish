<?php

namespace Moonlight\Controllers;

use Log;
use Validator;
use Illuminate\Http\Request;
use Moonlight\Main\LoggedUser;
use Moonlight\Main\Element;
use Moonlight\Main\UserActionType;
use Moonlight\Models\UserAction;

class EditController extends Controller
{
    /**
     * Save element.
     *
     * @return Response
     */
    public function save(Request $request, $classId)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
		$element = Element::getByClassId($classId);
        
        if ( ! $element) {
            $scope['error'] = 'Элемент не найден.';
            
            return response()->json($scope);
        }
        
        $site = \App::make('site');

        $currentItem = $site->getItemByName($element->getClass());
		$mainProperty = $currentItem->getMainProperty();
        
        $propertyList = $currentItem->getPropertyList();

        $input = [];
		$rules = [];
		$messages = [];

		foreach ($propertyList as $propertyName => $property) {
			if (
				$property->getHidden()
				|| $property->getReadonly()
			) continue;
            
            $input[$propertyName] = $property->setRequest($request)->buildInput();

			foreach ($property->getRules() as $rule => $message) {
				$rules[$propertyName][] = $rule;
				if (strpos($rule, ':')) {
					list($name, $value) = explode(':', $rule, 2);
					$messages[$propertyName.'.'.$name] = '<b>'.$property->getTitle().'.</b> '.$message;
				} else {
					$messages[$propertyName.'.'.$rule] = '<b>'.$property->getTitle().'.</b> '.$message;
				}
			}
		}
        
        $validator = Validator::make($input, $rules, $messages);
        
        if ($validator->fails()) {
            $messages = $validator->errors();
            
            foreach ($propertyList as $propertyName => $property) {
                if ($messages->has($propertyName)) {
                    $scope['errors'][$propertyName] = $messages->first($propertyName);
                }
            }
        }
        
        if (isset($scope['errors'])) {
            return response()->json($scope);
        }

        foreach ($propertyList as $propertyName => $property) {
			if (
				$property->getHidden()
				|| $property->getReadonly()
				|| $property instanceof OrderProperty
			) continue;

			$property->
                setRequest($request)->
                setElement($element)->
                set();
		}

        $element->save();
        
        UserAction::log(
			UserActionType::ACTION_TYPE_SAVE_ELEMENT_ID,
			'ID '.$element->getClassId()
		);
        
        $views = [];
        
        foreach ($propertyList as $propertyName => $property) {
            if ($view = $property->setElement($element)->getEditView()) {
                $views[$propertyName] = $view;
            }
        }
        
        $scope['saved'] = $element->getClassId();
        $scope['views'] = $views;
        
        return response()->json($scope);
    }
    
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