<?php

use Illuminate\Database\Seeder;

class GoodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('goods')->insert([
            'name' => 'Апельсины',
			'order' => 1,
			'url' => 'oranges',
			'supplier_price' => 100,
			'price' => 120,
			'shortcontent' => '',
			'fullcontent' => '',
			'category_id' => 1,
        ]);
        
        DB::table('goods')->insert([
            'name' => 'Яблоки',
			'order' => 2,
			'url' => 'apples',
			'supplier_price' => 80,
			'price' => 100,
			'shortcontent' => '',
			'fullcontent' => '',
			'category_id' => 1,
        ]);
        
        DB::table('goods')->insert([
            'name' => 'Груши',
			'order' => 3,
			'url' => 'peaches',
			'supplier_price' => 150,
			'price' => 180,
			'shortcontent' => '',
			'fullcontent' => '',
			'category_id' => 1,
        ]);
    }
}
