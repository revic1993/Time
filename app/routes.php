<?php


//Filters

Route::filter("authenticate", function(){
	$inputs = Input::all();
	$index =  View::make('layouts.index');

	try {
		
		$user = User::where('email','=',$inputs["email"])->firstOrFail();
	} catch (Exception $e) {
		$index->email="";
		$index->error="email";
		$index->message="Check your email";
		return $index;
	}
	if(!Hash::check($inputs["password"], $user->password)){
		$index->email=$user->email;
		$index->error="password";
		$index->message="Incorrect Password.";
		return $index;
	}

// dd($user->username);
	Session::put("username", $user->username);
	Session::put("user_id",$user->id);
	
});

Route::filter("check",function(){
	if(Session::has("user"))
		return Redirect::to("/dashboard");
});

//Routes
Route::get('/', ["before"=>"check",function()
{
	$index =  View::make('layouts.index');
	$index->email="";
	$index->error="";
	$index->message="";
	return $index;
}]);

Route::controller("/dashboard","PanelController");
Route::resource("/tags","TagsController");
Route::post("/",["before"=>"authenticate",function(){
	Session::put("user",Input::get("email"));
	return Redirect::to("/dashboard");
}]);

Route::get("/add",function(){
	$user = new User;
	$user->username = "Rujul Solanki";
	$user->email = "rujulsolanki1993@gmail.com";
	$user->password= Hash::make("lordsith123");
	$user->save();
});
