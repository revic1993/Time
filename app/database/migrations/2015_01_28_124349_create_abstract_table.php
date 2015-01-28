<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbstractTable extends Migration {

	
	public function up()
	{
		Schema::create("abstracts", function($table){
			$table->increments("id");
			$table->integer('date_id')->unsigned();
			$table->foreign('date_id')->references('id')->on('dates');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('tag_id')->unsigned();
			$table->foreign('tag_id')->references('id')->on('tags');
			$table->integer("hours");
		});
	}


	public function down()
	{
		Schema::create("abstracts");		
	}

}
