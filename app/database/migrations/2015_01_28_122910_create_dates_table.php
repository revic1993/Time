<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatesTable extends Migration {

	public function up()
	{
		Schema::create("dates", function($table){
			$table->increments("id");
			$table->date("date");
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->tinyInteger("isComplete");
		});
	}

	
	public function down()
	{
		Schema::drop("dates");
	}

}
