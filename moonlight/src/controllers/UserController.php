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
     * Add user.
     *
     * @return Response
     */
    public function add(Request $request)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        if ( ! $loggedUser->hasAccess('admin')) {
            $scope['error'] = 'У вас нет прав на управление пользователями.';
        } else {
            $scope['error'] = null;
        }
        
        if ($scope['error']) {
            return response()->json($scope);
        }
        
        $validator = Validator::make($request->all(), [
            'login' => 'required|max:25',
            'password' => 'required|min:6|max:25',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email',
            'groups' => 'array',
        ], [
            'login.required' => 'Введите логин.',
            'login.max' => 'Слишком длинный логин.',
            'password.required' => 'Введите пароль.',
            'password.min' => 'Минимальная длина пароля 6 символов.',
            'password.max' => 'Максимальная длина пароля 25 символов.',
            'first_name.required' => 'Введите имя.',
            'first_name.max' => 'Слишком длинное имя.',
            'last_name.required' => 'Введите фамилию.',
            'last_name.max' => 'Слишком длинная фамилия.',
            'email.required' => 'Введите адрес электронной почты.',
            'email.email' => 'Некорректный адрес электронной почты.',
            'groups.array' => 'Некорректные группы.',
        ]);
        
        if ($validator->fails()) {
            $messages = $validator->errors();
            
            foreach ([
                'login',
                'password',
                'first_name',
                'last_name',
                'email',
            ] as $field) {
                if ($messages->has($field)) {
                    $scope['errors'][$field] = $messages->first($field);
                }
            }
        }
        
        if (isset($scope['errors'])) {
            return response()->json($scope);
        }   
        
        $user = new User;

        $user->login = $request->input('login');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        
        /*
         * Set password
         */
        
        $password = $request->input('password');
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        
        $user->save();
        
        /*
         * Set groups
         */
        
        $groups = $request->input('groups');

        if ($groups) {
            foreach ($groups as $id) {
                $group = Group::find($id); 
                
                if ($group) {
                    $user->addGroup($group);
                }
            }
        }
        
        $scope['added'] = [
            'login' => $user->login,
            'password' => null,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
        ];
        
        return response()->json($scope);
    }
    
    /**
     * Save user.
     *
     * @return Response
     */
    public function save(Request $request, $id)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
		$user = User::find($id);
        
        if ( ! $loggedUser->hasAccess('admin')) {
            $scope['error'] = 'У вас нет прав на управление пользователями.';
        } elseif ( ! $user) {
            $scope['error'] = 'Пользователь не найден.';
        } elseif ($user->id == $loggedUser->id) {
            $scope['error'] = 'Нельзя редактировать самого себя.';
        } elseif ($user->isSuperUser()) {
            $scope['error'] = 'Нельзя редактировать суперпользователя.';
        } else {
            $scope['error'] = null;
        }
        
        if ($scope['error']) {
            return response()->json($scope);
        }
        
        $validator = Validator::make($request->all(), [
            'login' => 'required|max:25',
            'password' => 'min:6|max:25',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email',
            'groups' => 'array',
        ], [
            'login.required' => 'Введите логин.',
            'login.max' => 'Слишком длинный логин.',
            'password.min' => 'Минимальная длина пароля 6 символов.',
            'password.max' => 'Максимальная длина пароля 25 символов.',
            'first_name.required' => 'Введите имя.',
            'first_name.max' => 'Слишком длинное имя.',
            'last_name.required' => 'Введите фамилию.',
            'last_name.max' => 'Слишком длинная фамилия.',
            'email.required' => 'Введите адрес электронной почты.',
            'email.email' => 'Некорректный адрес электронной почты.',
            'groups.array' => 'Некорректные группы.',
        ]);
        
        if ($validator->fails()) {
            $messages = $validator->errors();
            
            foreach ([
                'login',
                'password',
                'first_name',
                'last_name',
                'email',
            ] as $field) {
                if ($messages->has($field)) {
                    $scope['errors'][$field] = $messages->first($field);
                }
            }
        }
        
        if (isset($scope['errors'])) {
            return response()->json($scope);
        }        

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        
        /*
         * Set groups
         */
        
        $groups = $request->input('groups');
        
        $userGroups = $user->getGroups();
        
        foreach ($userGroups as $group) {
			if ( ! $groups || ! in_array($group->id, $groups)) {
				$user->removeGroup($group);
			}
		}

        if ($groups) {
            foreach ($groups as $id) {
                $group = Group::find($id); 
                
                if ($group) {
                    $user->addGroup($group);
                }
            }
        }
        
        /*
         * Set password
         */
        
        $password = $request->input('password');
        
        if ($password) {
            $user->password = password_hash($password, PASSWORD_DEFAULT);
        }
        
        $user->save();
        
        $scope['saved'] = [
            'login' => $user->login,
            'password' => null,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
        ];
        
        return response()->json($scope);
    }
    
    /**
     * Create user.
     * @return Response
     */
    public function create(Request $request)
    {
        $scope = [];
        
        $loggedUser = LoggedUser::getUser();
        
        if ( ! $loggedUser->hasAccess('admin')) {
            return redirect()->route('users');
        }
        
        $groups = Group::orderBy('name', 'asc')->get();
        
        $scope['user'] = null;
        $scope['groups'] = $groups;
        $scope['userGroups'] = [];
        
        return view('moonlight::user', $scope);
    }
    
    /**
     * Edit user.
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
        
        $groups = Group::orderBy('name', 'asc')->get();

        $userGroups = [];
        
        foreach ($user->getGroups() as $group) {
            $userGroups[$group->id] = $group->id;
        }
        
        $scope['user'] = $user;
        $scope['groups'] = $groups;
        $scope['userGroups'] = $userGroups;
        
        return view('moonlight::user', $scope);
    }
}