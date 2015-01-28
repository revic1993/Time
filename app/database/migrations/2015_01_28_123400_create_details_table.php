<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailsTable extends Migration {


	public function up()
	{
		Schema::create("details", function($table){
			$table->increments("id");
			$table->integer('date_id')->unsigned();
			$table->foreign('date_id')->references('id')->on('dates');
			$table->integer("from");
			$table->integer("to");
			$table->string("tagName");
			$table->string("tagColor");
		});
	}


	public function down()
	{
		Schema::drop("details");
	}

}
