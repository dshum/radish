<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('order');
			$table->integer('service_section_id')->unsigned()->nullable()->default(null)->index();
			$table->timestamps();
			$table->softDeletes(); 
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('expense_categories');
    }
}
