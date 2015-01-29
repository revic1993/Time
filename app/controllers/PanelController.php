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
			return Response::json(["error"=>true,"message"=>"no such user exist"], 404);
		}
		try {
			$dates= Date::where("user_id",$id)->where("date",$date)->firstOrFail();
			if($dates->isComplete==1)
			   return Response::json(["error"=>true,"msg"=>"Date is already completed."], 404);
		} catch (Exception $e) {
			return Response::json(["error"=>false,"data"=>$this->createTimeList(),"count"=>0], 200);
		}
		$details = Detail::where("date_id",$dates->id)->get();
		
		if($details->count()==0)
			return Response::json(["error"=>false,"data"=>$this->createTimeList(),"count"=>0], 200);
		
		$postData = array();
		$data = array();
		$notPresent = array();
		foreach ($details as $detail) {
					$postData["from"] = ($detail->from%12)?$detail->from%12:12;
			 		$postData["to"] = ($detail->to%12)?$detail->to%12:12;
			 		$postData["ftZone"] =($postData["from"]<12)?"a.m.":"p.m";
			 		$postData["ttZone"] =($postData["to"]<12)?"a.m.":"p.m";
			 		$postData["isTag"] = true;
		 	 		$postData["tag"] = $detail->tagName;
		 	 		$postData["tagColor"] = $detail->tagColor;
			 		$postData["index"]= $postData["from"];
			 		$postData["isSaved"] = true;
					array_push($data, $postData);
		}

		$count = count($details);

		if($count<=24)
		{
			$start = $details[$count-1]["from"]+1;
			for($i=$start;$i<=24;$i++)
			{
				$postData["from"] = ($i%12)?$i%12:12;
				$postData["to"] = (($i+1)%12)?($i+1)%12:12;
				$postData["ftZone"] =($postData["from"]<12)?"a.m.":"p.m";
				$postData["ttZone"] =($postData["to"]<12)?"a.m.":"p.m";
				$postData["isTag"] = false;
				$postData["tag"] ="" ;
				$postData["tagColor"] = "#18BC9C";
				$postData["index"]= $i;
				$postData["isSaved"] = false;
				array_push($data, $postData);
			}
		}


		return Response::json(["error"=>false,"data"=>$data,"count"=>$count], 200);
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
			if($dates->isComplete==1)
			   return Response::json(["msg"=>"Date is already completed."], 200);
		} catch (Exception $e) {
			$dates = new Date;
			$dates->date = $input["date"];
			$dates->user_id = $input["user_id"];
			$dates->isComplete = 0;
			$dates->save();
		}

		$timeList = $input["time"];
		$tagCount = $input["count"];

		if($tagCount==24)
		{
			$tags = array();
			$tagList = array();

			foreach($timeList as $time)
			{
				$ind  = $time["tag"]."";				
				if(!array_key_exists($ind, $tags))
				{
					$tags[$ind]=array();
					$tags[$ind]["color"] = $time["tagColor"];
					$tags[$ind]["count"] = 0;
				}
				$tags[$ind]["count"]++;
			}
			foreach($tags as $key => $value)
			{
				$abstract = new Abstracts;
				$abstract->date_id = $dates->id;
				$abstract->tagName = $key;
				$abstract->tagColor = $value["color"];
				$abstract->hours = $value["count"];
				$abstract->save();
			}
			DB::table('details')->where('date_id', '=', $dates->id)->delete();
			$dates->isComplete = 1;
			$dates->save();
			return Response::json(["error"=>false,"message"=>"Done"], 200);
		}

		$count = 0;
		foreach ($timeList as $time) {
			$detail = new Detail;
			if($time["isTag"])
			{	
				$count++;
				if(!$time["isSaved"]){
					$i = $time["index"];					
					$detail->date_id = $dates->id;
					$detail->from = $i;
			 		$detail->to = $i+1;
				}else{
					try {
						$detail = Detail::where("date_id",$dates->id)->where("from",$time["from"])->firstOrFail();
					} catch (Exception $e) {
						return Response::json(["error"=>true,"message"=>$e->getMessage()], 404);
					}	
				}
				$detail->tagName = $time["tag"];
				$detail->tagColor = $time["tagColor"];
				$detail->save();
			}
			else{
				if($time["isSaved"])
				{
					$detail = Detail::where("date_id",$dates->id)->where("from",$time["from"])->firstOrFail();
					$detail->delete();
				}
			}
		}

		return Response::json(["error"=>false,"message"=>"Done"], 200);
	}

	public function createTimeList()
	{
		$postData = array();
		$data = array();
		for($i=1;$i<=24;$i++)
		{
			$postData["from"] = ($i%12)?$i%12:12;
			$postData["to"] = (($i+1)%12)?($i+1)%12:12;
			$postData["ftZone"] =($postData["from"]<12)?"a.m.":"p.m";
			$postData["ttZone"] =($postData["to"]<12)?"a.m.":"p.m";
			$postData["isTag"] = false;
			$postData["tag"] ="" ;
			$postData["tagColor"] = "#18BC9C";
			$postData["index"]= $i;
			$postData["isSaved"] = false;
			array_push($data, $postData);
		}
		return $data;
	}
	
}
