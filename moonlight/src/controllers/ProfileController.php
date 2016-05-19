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
            'password' => 'min:6|max:25',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email',
        ], [
            'password.min' => 'Минимальная длина пароля 6 символов.',
            'password.max' => 'Максимальная длина пароля 25 символов.',
            'first_name.required' => 'Введите имя.',
            'first_name.max' => 'Слишком длинное имя.',
            'last_name.required' => 'Введите фамилию.',
            'last_name.max' => 'Слишком длинная фамилия.',
            'email.required' => 'Введите адрес электронной почты.',
            'email.email' => 'Некорректный адрес электронной почты.',
        ]);
        
        if ($validator->fails()) {
            $messages = $validator->errors();
            
            foreach ([
                'password',
                'first_name',
                'last_name',
                'email',
            ] as $field) {
                if ($messages->has($field)) {
                    $scope['errors'][$field] = $messages->first($field);
                }
            }

            return response()->json($scope);
        }
        
        $loggedUser->first_name = $request->input('first_name');
        $loggedUser->last_name = $request->input('last_name');
        $loggedUser->email = $request->input('email');
        
        $password = $request->input('password');
        
        if ($password) {
            $loggedUser->password = password_hash($password, PASSWORD_DEFAULT);
        }
        
        $loggedUser->save();
        
        $scope['user'] = [
            'password' => null,
            'first_name' => $loggedUser->first_name,
            'last_name' => $loggedUser->last_name,
            'email' => $loggedUser->email,
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