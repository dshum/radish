<?php

namespace Moonlight\Controllers;

use Illuminate\Http\Request;
use Moonlight\Main\LoggedUser;
use Moonlight\Main\Element;
use Moonlight\Properties\OrderProperty;
use Moonlight\Properties\DateProperty;
use Moonlight\Properties\DatetimeProperty;
use Moonlight\Models\FavoriteRubric;
use Moonlight\Models\Favorite;

class BrowseController extends Controller
{
    /**
     * Return the count of element list.
     *
     * @return Response
     */
    public function count(Request $request)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        $class = $request->input('item');
        $classId = $request->input('classId');
        
        $site = \App::make('site');
        
        $item = $site->getItemByName($class);
        
        if ( ! $item) {
            return response()->json(['count' => 0]);
        }
        
        $element = $classId 
            ? Element::getByClassId($classId) : null;

		if ( ! $element && ! $item->getRoot()) {
			return response()->json(['count' => 0]);
		}
        
        $propertyList = $item->getPropertyList();

		if ($element) {
			$flag = false;
            
			foreach ($propertyList as $propertyName => $property) {
				if (
					$property->isOneToOne()
					&& $property->getRelatedClass() == $element->getClass()
				) $flag = true;
			}
            
			if ( ! $flag) {
				return response()->json(['count' => 0]);
			}
		}

		if ( ! $loggedUser->isSuperUser()) {
			$permissionDenied = true;
			$deniedElementList = [];
			$allowedElementList = [];

			$groupList = $loggedUser->getGroups();

			foreach ($groupList as $group) {
				$itemPermission = $group->getItemPermission($item->getNameId())
					? $group->getItemPermission($item->getNameId())->permission
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
					
                    if ($class == $item->getNameId()) {
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

        $criteria = $item->getClass()->where(
            function($query) use ($propertyList, $element) {
                if ($element) {
                    $query->orWhere('id', null);
                }

                foreach ($propertyList as $propertyName => $property) {
                    if (
                        $element
                        && $property->isOneToOne()
                        && $property->getRelatedClass() == $element->getClass()
                    ) {
                        $query->orWhere(
                            $property->getName(), $element->id
                        );
                    } elseif (
                        ! $element
                        && $property->isOneToOne()
                    ) {
                        $query->orWhere(
                            $property->getName(), null
                        );
                    }
                }
            }
        );

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

		$count = $criteria->count();
        usleep(10000 * $count);
        
        $scope['count'] = $count;
            
        return response()->json($scope);
    }
    
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
        $classId = $request->input('classId');
        
        $site = \App::make('site');
        
        $currentItem = $site->getItemByName($class);
        
        if ( ! $currentItem) {
            return redirect()->route('browse');
        }
        
        $element = $classId 
            ? Element::getByClassId($classId) : null;
        
        $itemList = $site->getItemList();

		$items = [];

		foreach ($itemList as $itemName => $item) {
			if ( ! $item->getRoot()) continue;

			$items[] = $item;
		}
        
        $propertyList = $currentItem->getPropertyList();

		if ( ! $loggedUser->isSuperUser()) {
			$permissionDenied = true;
			$deniedElementList = [];
			$allowedElementList = [];

			$groupList = $loggedUser->getGroups();

			foreach ($groupList as $group) {
				$itemPermission = $group->getItemPermission($item->getNameId())
					? $group->getItemPermission($item->getNameId())->permission
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
					
                    if ($class == $item->getNameId()) {
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

        $criteria = $currentItem->getClass()->where(
            function($query) use ($propertyList, $element) {
                if ($element) {
                    $query->orWhere('id', null);
                }

                foreach ($propertyList as $property) {
                    if (
                        $element
                        && $property->isOneToOne()
                        && $property->getRelatedClass() == $element->getClass()
                    ) {
                        $query->orWhere(
                            $property->getName(), $element->id
                        );
                    } elseif (
                        ! $element
                        && $property->isOneToOne()
                    ) {
                        $query->orWhere(
                            $property->getName(), null
                        );
                    }
                }
            }
        );

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
        usleep(10000 * $elements->total());
        
        $total = $elements->total();
		$currentPage = $elements->currentPage();
        $hasMorePages = $elements->hasMorePages();

        $scope['currentElement'] = $element;
        $scope['currentItem'] = $currentItem;
        $scope['total'] = $total;
        $scope['currentPage'] = $currentPage;
        $scope['hasMorePages'] = $hasMorePages;
        $scope['elements'] = $elements;
        $scope['orders'] = $orders;
        
        $html = view('moonlight::elements', $scope)->render();
        
        return response()->json(['html' => $html]);
    }
    
    /**
     * Show element list for autocomplete.
     *
     * @return Response
     */
    public function autocomplete(Request $request)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        $class = $request->input('item');
        $query = $request->input('query');
        
        $site = \App::make('site');
        
        $currentItem = $site->getItemByName($class);
        
        if ( ! $currentItem) {
            return response()->json($scope);
        }
        
        $mainProperty = $currentItem->getMainProperty();

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
					
                    if ($class == $item->getNameId()) {
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
        
        if ($query) {
            $criteria->whereRaw(
                "cast(id as text) ilike :query or $mainProperty ilike :query",
                ['query' => '%'.$query.'%']
            );
        }

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

		foreach ($orderByList as $field => $direction) {
            $criteria->orderBy($field, $direction);
        }

		$elements = $criteria->limit(10)->get();
        
        $scope['suggestions'] = [];
        
        foreach ($elements as $element) {
            $scope['suggestions'][] = [
                'value' => $element->$mainProperty,
                'data' => $element->getClassId(),
            ];
        }
        
        return response()->json($scope);
    }
    
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
     * Show browse element.
     *
     * @return View
     */
    public function element(Request $request, $classId)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        $element = Element::getByClassId($classId);
        
        if ( ! $element) {
            return redirect()->route('browse');
        }
        
        $currentItem = $element->getItem();
        
        $parent = Element::getParent($element);
        
        $site = \App::make('site');
        
        $itemList = $site->getItemList();

		$items = [];
        
        foreach ($itemList as $item) {
            $propertyList = $item->getPropertyList();

            foreach ($propertyList as $property) {
                if (
                    $property->isOneToOne()
                    && $property->getRelatedClass() == $element->getClass()
                ) {
                    $items[] = $item;
                    break;
                }
            }
		}
        
        $favorite = Favorite::where('class_id', $classId)->first();

        $scope['element'] = $element;
        $scope['parent'] = $parent;
        $scope['currentItem'] = $currentItem;
		$scope['items'] = $items;
        $scope['favorite'] = $favorite;
            
        return view('moonlight::element', $scope);
    }
    
    /**
     * Show browse root.
     *
     * @return View
     */
    public function root(Request $request)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        $site = \App::make('site');
        
        $itemList = $site->getItemList();

		$items = [];

		foreach ($itemList as $itemName => $item) {
			if ( ! $item->getRoot()) continue;

			$items[] = $item;
		}

		$scope['items'] = $items;
            
        return view('moonlight::root', $scope);
    }
}