<?php

use Illuminate\Database\Seeder;

class ServiceSectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('service_sections')->insert([
            'name' => 'Справочники',
			'order' => 1,
        ]);
        
        DB::table('service_sections')->insert([
            'name' => 'Заказы',
			'order' => 2,

        ]);
        
        DB::table('service_sections')->insert([
            'name' => 'Покупатели',
			'order' => 3,
        ]);
        
        DB::table('service_sections')->insert([
            'name' => 'Расходы',
			'order' => 4,
        ]);
        
        DB::table('service_sections')->insert([
            'name' => 'Счетчики',
			'order' => 5,
        ]);
        
        DB::table('service_sections')->insert([
            'name' => 'Инструменты',
			'order' => 6,
        ]);
        
        DB::table('service_sections')->insert([
            'name' => 'Статистика',
			'order' => 7,
        ]);
        
        DB::table('service_sections')->insert([
            'name' => 'Выручка',
			'order' => 8,
			'service_section_id' => 7,
        ]);
        
        DB::table('service_sections')->insert([
            'name' => 'Расходы',
			'order' => 9,
			'service_section_id' => 7,
        ]);
    }
}
