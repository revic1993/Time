<?php

class PanelController extends \BaseController {

	protected $layout="layouts.panel.master";
	public function getIndex()
	{	
		if(!$this->isLoggedIn())
			Redirect::to("/dashboard/logout");
		$this->layout->username = Session::get("username");	
	}

	public function getTime()
	{
		if(!$this->isLoggedIn())
			Redirect::to("/dashboard/logout");
		$this->layout->container = View::make("layouts.panel.time");
		$this->layout->container->username = Session::get("username");
		$this->layout->container->id=Session::get("user_id");
	} 

	public function getDate($id,$date)
	{

		try {
			$user = User::findOrFail($id);
		} catch (Exception $e) {
			return Response::json(["error"=>true,"message"=>$e->getMessage()], 404);
		}
		try {
			$dates= Date::where("user_id",$id)->where("date",$date)->firstOrFail();
		} catch (Exception $e) {
			return Response::json(["error"=>true,"message"=>$e->getMessage()], 200);
		}
		$details = Detail::where("date_id",$dates->id)->get();
		if($details->count()==0)
			return Response::json(["error"=>true,"message"=>"empty"], 200);
		
		$postData = array();
		$data = array();
		foreach ($details as $detail) {
						$postData["from"] = ($detail->from%12)?$detail->from%12:12;
			 			$postData["to"] = ($detail->to%12)?$detail->to%12:12;
			 			$postData["ftZone"] =($postData["from"]<12)?"a.m.":"p.m";
			 			$postData["ttZone"] =($postData["to"]<12)?"a.m.":"p.m";
			 			$postData["isTag"] = true;
		 	 			$postData["tag"] = $detail->tagName;
		 	 			$postData["tagColor"] = $detail->tagColor;
			 			$postData["index"]= $postData["from"];
						array_push($data, $postData);
		}
		return Response::json(["error"=>false,"data"=>$data], 200);
	}	

	public function getLogout()
	{
		Session::clear();
		return Redirect::to("/");
	}

	public function postDates()
	{
		$input = Input::get("data");

		try {
			$user = User::findOrFail($input["user_id"]);
		} catch (Exception $e) {
			return Response::json(["error"=>true,"message"=>"No such user exist!"], 404);
		}

		try {
			$dates = Date::where("user_id",$input["user_id"])->where("date",$input["date"])->firstOrFail();
		} catch (Exception $e) {
			$dates = new Date;
			$dates->date = $input["date"];
			$dates->user_id = $input["user_id"];
			$dates->isComplete = 0;
			$dates->save();
		}

		$timeList = $input["time"];

		foreach ($timeList as $time) {
			if($time["isTag"])
			{
				$i = $time["index"];
				$detail = new Detail;
				$detail->date_id = $dates->id;
				$detail->from = $i;
			 	$detail->to = $i+1;
			 	$detail->tagName = $time["tag"];
			 	$detail->tagColor = $time["tagColor"];
			 	$detail->save();
			}
		}

		return Response::json(["error"=>false,"message"=>"Done"], 200);
	}

	
}
