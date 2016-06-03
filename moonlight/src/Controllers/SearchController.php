<?php

namespace Moonlight\Controllers;

use Illuminate\Http\Request;
use Moonlight\Main\LoggedUser;
use Moonlight\Main\Element;
use Moonlight\Properties\OrderProperty;

class SearchController extends Controller
{
    /**
     * Show element list.
     *
     * @return Response
     */
    public function elements(Request $request)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        $class = $request->input('item');
        
        $site = \App::make('site');
        
        $currentItem = $site->getItemByName($class);
        
        if ( ! $currentItem) {
            return response()->json([]);
        }
        
        $elements = $this->elementListView($currentItem);
        
        return response()->json(['html' => $elements]);
    }
    
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
            if ($property->getName() == 'deleted_at') continue;

			$properties[] = $property->setRequest($request);
		}
        
        $action = $request->input('action');
        
        if ($action == 'search') {
            $elements = $this->elementListView($currentItem);
        } else {
            $elements = null;
        }
        
        $scope['currentItem'] = $currentItem;
        $scope['properties'] = $properties;
        $scope['elementsView'] = $elements;
            
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
    
    protected function elementListView($currentItem)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        $propertyList = $currentItem->getPropertyList();

		if ( ! $loggedUser->isSuperUser()) {
			$permissionDenied = true;
			$deniedElementList = [];
			$allowedElementList = [];

			$groupList = $loggedUser->getGroups();

			foreach ($groupList as $group) {
				$itemPermission = $group->getItemPermission($currentItem->getNameId())
					? $group->getItemPermission($currentItem->getNameId())->permission
					: $group->default_permission;

				if ($itemPermission != 'deny') {
					$permissionDenied = false;
					$deniedElementList = [];
				}

				$elementPermissionList = $group->elementPermissions;

				$elementPermissionMap = [];

				foreach ($elementPermissionList as $elementPermission) {
					$classId = $elementPermission->class_id;
					$permission = $elementPermission->permission;
                    
					$array = explode(Element::ID_SEPARATOR, $classId);
                    $id = array_pop($array);
                    $class = implode(Element::ID_SEPARATOR, $array);
					
                    if ($class == $currentItem->getNameId()) {
						$elementPermissionMap[$id] = $permission;
					}
				}

				foreach ($elementPermissionMap as $id => $permission) {
					if ($permission == 'deny') {
						$deniedElementList[$id] = $id;
					} else {
						$allowedElementList[$id] = $id;
					}
				}
			}
		}

        $criteria = $currentItem->getClass()->query();

		if ( ! $loggedUser->isSuperUser()) {
			if (
				$permissionDenied
				&& sizeof($allowedElementList)
			) {
				$criteria->whereIn('id', $allowedElementList);
			} elseif (
				! $permissionDenied
				&& sizeof($deniedElementList)
			) {
				$criteria->whereNotIn('id', $deniedElementList);
			} elseif ($permissionDenied) {
                return response()->json(['count' => 0]);
			}
		}
        
        $orderByList = $currentItem->getOrderByList();
        
        $orders = [];

		foreach ($orderByList as $field => $direction) {
            $criteria->orderBy($field, $direction);
            $property = $currentItem->getPropertyByName($field);
            if ($property instanceof OrderProperty) {
                $orders[$field] = 'порядку';
            } elseif ($property->getName() == 'created_at') {
                $orders[$field] = 'дате создания';
            } elseif ($property->getName() == 'updated_at') {
                $orders[$field] = 'дате изменения';
            } elseif ($property->getName() == 'deleted_at') {
                $orders[$field] = 'дате удаления';
            } else {
                $orders[$field] = 'полю &laquo;'.$property->getTitle().'&raquo;';
            }
        }
        
        $orders = implode(', ', $orders);

		$elements = $criteria->paginate(10);
        
        $total = $elements->total();
		$currentPage = $elements->currentPage();
        $hasMorePages = $elements->hasMorePages();

        $scope['currentItem'] = $currentItem;
        $scope['total'] = $total;
        $scope['currentPage'] = $currentPage;
        $scope['hasMorePages'] = $hasMorePages;
        $scope['elements'] = $elements;
        $scope['orders'] = $orders;
        
        $html = view('moonlight::elements', $scope)->render();
        
        return $html;
    }
}