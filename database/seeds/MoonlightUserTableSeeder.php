<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Moonlight\Models\User;
use Moonlight\Models\Group;

class MoonlightUserTableSeeder extends Seeder {

	public function run()
	{
		DB::table('admin_users')->insert([
			'login' => 'magus',
			'password' => 'secret',
			'email' => 'denis-shumeev@yandex.ru',
			'first_name' => 'Super',
			'last_name' => 'Magus',
			'superuser' => true,
		]);

		DB::table('admin_users')->insert([
			'login' => 'denis',
			'password' => 'qwerty',
			'email' => 'denis-shumeev@yandex.ru',
			'first_name' => 'Denis',
			'last_name' => 'Shumeev',
			'superuser' => false,
		]);
        
        DB::table('admin_users')->insert([
			'login' => 'stepa',
			'password' => 'qwerty',
			'email' => 'stepenin@yandex.ru',
			'first_name' => 'Андрей',
			'last_name' => 'Степенин',
			'superuser' => false,
		]);
        
        DB::table('admin_users')->insert([
			'login' => 'vera',
			'password' => 'qwerty',
			'email' => 'vegorova@mail.ru',
			'first_name' => 'Вера',
			'last_name' => 'Егорова',
			'superuser' => false,
		]);
        
        DB::table('admin_users')->insert([
			'login' => 'valeria',
			'password' => 'qwerty',
			'email' => 'valeria-guzhvinskaya@yandex.ru',
			'first_name' => 'Валерия',
			'last_name' => 'Гужвинская',
			'superuser' => false,
		]);
	}
}
