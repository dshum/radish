<?php

use Illuminate\Database\Seeder;

class SiteSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('site_settings')->insert([
            'name' => 'Настройки сайта',
			'title' => 'Фрукты и Овощи',
			'meta_keywords' => 'фрукты, ягоды, овощи, Garden Radish',
			'meta_description' => 'Фрукты и Овощи, Garden Radish',
        ]);
    }
}
