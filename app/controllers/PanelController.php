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

	public function getLogout()
	{
		Session::clear();
		return Redirect::to("/");
	}

	
}
