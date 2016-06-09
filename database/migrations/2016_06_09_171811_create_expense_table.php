<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
            $table->string('order');
            $table->integer('category_id')->unsigned()->nullable()->default(null)->index();
            $table->integer('source_id')->unsigned()->nullable()->default(null)->index();
            $table->double('sum')->nullable();
            $table->text('comments')->nullable();
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
        Schema::drop('expenses');
    }
}
