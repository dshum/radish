<?php

namespace Moonlight\Controllers;

use Validator;
use Illuminate\Http\Request;
use Moonlight\Main\LoggedUser;
use Moonlight\Models\User;

class ProfileController extends Controller
{
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
     * Show the profile of the logged user.
     *for the given user
     * @return Response
     */
    public function show(Request $request)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        $groups = $loggedUser->getGroups();
        
        $scope['login'] = $loggedUser->login;
        $scope['first_name'] = $loggedUser->first_name;
        $scope['last_name'] = $loggedUser->last_name;
        $scope['email'] = $loggedUser->email;
        $scope['created_at'] = $loggedUser->created_at;
        $scope['last_login'] = $loggedUser->last_login;
        $scope['groups'] = $loggedUser->groups;
        
        return view('moonlight::profile', $scope);
    }
}