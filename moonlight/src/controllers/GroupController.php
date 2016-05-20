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
     * Save group.
     *
     * @return Response
     */
    public function save(Request $request, $id)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
		$group = Group::find($id);
        
        if ( ! $loggedUser->hasAccess('admin')) {
            $scope['error'] = 'У вас нет прав на управление пользователями.';
        } elseif ( ! $group) {
            $scope['error'] = 'Группа не найдена.';
        } elseif ($loggedUser->inGroup($group)) {
            $scope['error'] = 'Нельзя редактировать группу, в которой вы состоите.';
        } else {
            $scope['error'] = null;
        }
        
        if ($scope['error']) {
            return response()->json($scope);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'default_permission' => 'required|in:deny,view,update,delete',
        ], [
            'name.required' => 'Введите название.',
			'default_permission.required' => 'Укажите доступ к элементам.',
			'default_permission.in' => 'Некорректный доступ.',
        ]);
        
        if ($validator->fails()) {
            $messages = $validator->errors();
            
            foreach ([
                'name',
                'default_permission',
            ] as $field) {
                if ($messages->has($field)) {
                    $scope['errors'][$field] = $messages->first($field);
                }
            }
        }
        
        if (isset($scope['errors'])) {
            return response()->json($scope);
        }
        
        $group->name = $request->input('name');
		$group->default_permission = $request->input('default_permission');
        
        $admin = $request->has('admin') ? true : false;
        
        $group->setPermission('admin', $admin);
        
        $group->save();
        
        $scope['group'] = [
            'name' => $group->name,
            'default_permission' => $group->default_permission,
        ];
        
        $group->save();
        
        $scope['group'] = $group;
        
        return response()->json($scope);
    }
    
    /**
     * Edit group.
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