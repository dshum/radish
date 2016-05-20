<?php

namespace Moonlight\Controllers;

use Validator;
use Illuminate\Http\Request;
use Moonlight\Main\LoggedUser;
use Moonlight\Models\Group;
use Moonlight\Models\User;

class GroupController extends Controller
{
    /**
     * Delete group.
     *
     * @return Response
     */
    public function delete(Request $request, $id)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
		$group = Group::find($id);
        
        if ( ! $loggedUser->hasAccess('admin')) {
            $scope['error'] = 'У вас нет прав на управление пользователями.';
        } elseif ( ! $group) {
            $scope['error'] = 'Группа не найдена.';
        } elseif ($loggedUser->inGroup($group)) {
            $scope['error'] = 'Нельзя удалить группу, в которой вы состоите.';
        } else {
            $scope['error'] = null;
        }
        
        if ($scope['error']) {
            return response()->json($scope);
        }
        
        $group->delete();
        
        $scope['group'] = $group->id;
        
        return response()->json($scope);
    }
    
    /**
     * Edit user.
     * 
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        if ( ! $loggedUser->hasAccess('admin')) {
            return redirect()->route('users');
        }
        
        $group = Group::find($id);
        
        if ( ! $group) {
            return redirect()->route('users');
        }
        
        $scope['group'] = $group;
        
        return view('moonlight::group', $scope);
    }
}