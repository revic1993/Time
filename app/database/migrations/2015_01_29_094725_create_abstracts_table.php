<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbstractsTable extends Migration {

	
	public function up()
	{
		Schema::create("abstracts", function($table){
			$table->increments("id");
			$table->integer('date_id')->unsigned();
			$table->foreign('date_id')->references('id')->on('dates');
			$table->string("tagName");
			$table->string("tagColor");
			$table->integer("hours");
		});
	}


	public function down()
	{
		Schema::create("abstracts");		
	}

}
