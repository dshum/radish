<?php

namespace Moonlight\Controllers;

use Illuminate\Http\Request;
use Moonlight\Main\LoggedUser;
use Moonlight\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $scope = [];

		$login = $request->input('login');
		$password = $request->input('password');

		if ( ! $login) {
			$scope['message'] = 'Введите логин.';
			return view('moonlight::login', $scope);
		}
        
        $scope['login'] = $login;

		if ( ! $password) {
			$scope['message'] = 'Введите пароль.';
			return view('moonlight::login', $scope);
		}

		$user = User::where('login', $login)->first();

		if ( ! $user) {
			$scope['message'] = 'Неправильный логин или пароль.';
			return view('moonlight::login', $scope);
		}

		if ($user->password != $password) {
			$scope['message'] = 'Неправильный логин или пароль.';
			return view('moonlight::login', $scope);
		}

		if ($user->banned) {
			$scope['message'] = 'Пользователь заблокирован.';
			return view('moonlight::login', $scope);
		}

        LoggedUser::login($user);

        return redirect()->route('home')->withCookie(cookie()->forever('login', $user->login));
    }
    
    public function logout(Request $request)
    {
        LoggedUser::logout();
        
        return redirect()->route('login');
    }
    
    public function show(Request $request)
    {
        $scope['login'] = $request->cookie('login');
        
        return view('moonlight::login', $scope);
    }
}