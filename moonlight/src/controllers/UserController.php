<?php

namespace Moonlight\Controllers;

use Validator;
use Illuminate\Http\Request;
use Moonlight\Main\LoggedUser;
use Moonlight\Models\Group;
use Moonlight\Models\User;

class UserController extends Controller
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
        
		$user = User::find($id);
        
        if ( ! $loggedUser->hasAccess('admin')) {
            $scope['error'] = 'У вас нет прав на управление пользователями.';
        } elseif ( ! $user) {
            $scope['error'] = 'Пользователь не найден.';
        } elseif ($user->id == $loggedUser->id) {
            $scope['error'] = 'Нельзя удалить самого себя.';
        } elseif ($user->isSuperUser()) {
            $scope['error'] = 'Нельзя удалить суперпользователя.';
        } else {
            $scope['error'] = null;
        }
        
        if ($scope['error']) {
            return response()->json($scope);
        }
        
        $user->delete();
        
        $scope['user'] = $user->id;
        
        return response()->json($scope);
    }
    
    /**
     * Save the profile of the logged user.
     *
     * @return Response
     */
    public function save(Request $request)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
		$validator = Validator::make($request->all(), [
            'password' => 'min:6|max:25|confirmed',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email',
        ], [
            'first_name.required' => 'Введите имя.',
            'first_name.max' => 'Слишком длинное имя.',
            'last_name.required' => 'Введите фамилию.',
            'last_name.max' => 'Слишком длинная фамилия.',
            'email.required' => 'Введите адрес электронной почты.',
            'email.email' => 'Некорректный адрес электронной почты.',
            'password.min' => 'Минимальная длина пароля 6 символов.',
            'password.max' => 'Максимальная длина пароля 25 символов.',
            'password.confirmed' => 'Введенные пароли должны совпадать.',
        ]);
        
        if ($validator->fails()) {
            $messages = $validator->errors();
            
            foreach ([
                'first_name',
                'last_name',
                'email',
                'password',
            ] as $field) {
                if ($messages->has($field)) {
                    $scope['errors'][$field] = $messages->first($field);
                }
            }
        }
        
        $password_old = $request->input('password_old');
        $password = $request->input('password');
        
        if (
            ($password || $password_old) 
            && ! password_verify($password_old, $loggedUser->password)) {
            $scope['errors']['password_old'] = 'Неправильный текущий пароль.';
        }
        
        if (isset($scope['errors'])) {
            return response()->json($scope);
        }
        
        $loggedUser->first_name = $request->input('first_name');
        $loggedUser->last_name = $request->input('last_name');
        $loggedUser->email = $request->input('email');
        
        if ($password) {
            $loggedUser->password = password_hash($password, PASSWORD_DEFAULT);
        }
        
        $loggedUser->save();
        
        $scope['user'] = [
            'first_name' => $loggedUser->first_name,
            'last_name' => $loggedUser->last_name,
            'email' => $loggedUser->email,
            'password_old' => null,
            'password' => null,
            'password_confirmation' => null,
        ];
        
        return response()->json($scope);
    }
    
    /**
     * Show the list of groups and users.
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        if ( ! $loggedUser->hasAccess('admin')) {
            return redirect()->route('users');
        }
        
        $user = User::find($id);
        
        if ( ! $user) {
            return redirect()->route('users');
        }
        
        $userGroups = $user->getGroups();
        
        $scope['user'] = $user;
        $scope['userGroups'] = $userGroups;
        
        return view('moonlight::user', $scope);
    }
}