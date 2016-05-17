<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'name' => 'Фрукты',
			'order' => 1,
			'url' => 'fruits',
			'title' => 'Фрукты',
			'shortcontent' => '',
			'fullcontent' => '',
        ]);
        
        DB::table('categories')->insert([
            'name' => 'Ягоды',
			'order' => 2,
			'url' => 'berries',
			'title' => 'Ягоды',
			'shortcontent' => '',
			'fullcontent' => '',
        ]);
        
        DB::table('categories')->insert([
            'name' => 'Овощи',
			'order' => 3,
			'url' => 'vegetables',
			'title' => 'Овощи',
			'shortcontent' => '',
			'fullcontent' => '',
        ]);
    }
}
