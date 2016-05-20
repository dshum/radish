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
     * Delete user.
     *
     * @return Response
     */
    public function delete(Request $request, $id)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
		$group = Group::find($id);
        
        if ( ! $group) {
            $scope['error'] = 'Группа не найдена.';
            
            return response()->json($scope);
        }
        
        if ($loggedUser->inGroup($group)) {
            $scope['error'] = 'Нельзя удалить группу, в которой вы состоите.';
            usleep(300000);
            
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
        
        $group = Group::find($id);
        
        if ( ! $group) {
            return redirect()->route('users');
        }
        
        $scope['group'] = $group;
        
        return view('moonlight::group', $scope);
    }
}