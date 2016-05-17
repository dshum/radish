<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'email' => 'denis-shumeev@yandex.ru',
			'password' => 'qwerty',
			'fio' => 'Шумеев Денис Валентинович',
			'phone' => '+7 926 3937226',
            'activated' => true,
            'banned' => false,
        ]);
    }
}
