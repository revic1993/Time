<?php

class TagsController extends \BaseController {

	protected $layout="layouts.panel.master";
	public function index()
	{
		if(!$this->isLoggedIn())
			return Redirect::to("/dashboard/logout");
		$this->layout->container=View::make("layouts.panel.tags");
		$this->layout->container->username = Session::get("username");
		$this->layout->container->id=Session::get("user_id");
	}	

	
	public function store()
	{
		$inputs = Input::all();
		$tagCheck= DB::table("tags")->where("user_id",$inputs["id"])->where("name",$inputs["tagName"])->count();

		$colorCheck = DB::table("tags")->where("user_id",$inputs["id"])->where("color",$inputs["color"])->count();

		if($tagCheck!=0)
		{
			return Response::json(["message"=>"already exists!","success"=>false,"type"=>"tag"],404);
		}
		if($colorCheck!=0)
			return Response::json(["message"=>"Color already exists!","success"=>false,"type"=>"color"],404);

		$tag = new Tag;
		try {
			$tag->user_id=$inputs["id"];
			$tag->name=$inputs["tagName"];
			$tag->color = $inputs["color"];
			$tag->save();	
		} catch (Exception $e) {
			return Response::json(["message"=>$e->getMessage(),"success"=>false],404);
		}
		$data = array();
		$data["tagId"] = $tag->id;
		$data["tagName"]= $tag->name;
		$data["color"] = $tag->color;
		return Response::json($data,200);
	}


	
	public function show($id)
	{
		try {
			$tags = Tag::where("user_id","=",$id)->get();	
		} catch (Exception $e) {
			return Response::json($e->getMessage, 404);
		}
		
		$tagList = array();
		foreach ($tags as $tag) {
			$temp["tagId"] = $tag->id;
			$temp["tagName"] = $tag->name;
			$temp["color"] = $tag->color;
			array_push($tagList, $temp); 
		}
		return Response::json($tagList, 200);
	}


	public function destroy($id)
	{
		try {
			$tag = Tag::findOrFail($id);
		} catch (Exception $e) {
			return Response::json($e->getMessage(), 404);
		}
		$tag->delete();
		return Response::json(["message"=>"successfully deleted tag"],200);
	}


}
