<?php

namespace Moonlight\Controllers;

use Illuminate\Http\Request;
use Moonlight\Main\LoggedUser;
use Moonlight\Models\Group;
use Moonlight\Models\User;

class UsersController extends Controller
{   
    /**
     * Show the list of groups and users.
     * 
     * @return Response
     */
    public function show(Request $request)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        $groups = Group::orderBy('name', 'asc')->get();
        $users = User::orderBy('login', 'asc')->get();
        
        foreach ($users as $user) {
            $userGroups[$user->id] = $user->getGroups();
        }
        
        $scope['groups'] = $groups;
        $scope['users'] = $users;
        $scope['userGroups'] = $userGroups;
        
        return view('moonlight::users', $scope);
    }
}