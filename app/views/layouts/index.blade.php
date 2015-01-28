<!DOCTYPE html>
<html>
<head>
	<title>Time Management</title>
	{{HTML::style("css/index.css")}}
	{{HTML::style("css/bootstrap.css")}}
</head>
<body>
	<?php
		$userClass="form-group";
		$passwordClass=$userClass;
		$passErr=$emailErr="";
		if($error!=""){
			if($error=="email")
				$emailErr = " has-error";
			else
				$passErr=" has-error";
		}
		else{
			$emailErr="";
			$passErr="";
		}
		
	?>
	<header>
		Time Management
	</header>
	<div class="container">

		<div class="col-lg-3"></div>
		<div class="col-lg-6">		
			{{Form::open(["url"=>" ","class"=>"form-horizontal"])}}
			<div class="{{$userClass.$emailErr}}">
			  <label for="email" class="col-lg-2 control-label">Email</label>
			  <div class="col-lg-10">
			    {{Form::text("email",$email,["class"=>"form-control","placeholder"=>"johndoe@host.com"])}}			
			  </div>
			</div>
			<div class="{{$passwordClass.$passErr}}">
			  <label for="password" class="col-lg-2 control-label">Password</label>
			  <div class="col-lg-10">
			    {{Form::password("password",["class"=>"form-control","placeholder"=>"Password"])}}		
			  </div>
			</div>
			<div class="form-group">
				<div class="col-lg-10"></div>
				{{Form::submit("Sign in",["class"=>"btn btn-primary"])}}	
			</div>
				
			{{Form::close()}}
			@if(!empty($error))
			<div class="errorAlert">			
				<div class="alert alert-dismissable alert-danger">
				  <button type="button" class="close" onclick="dismissDialog()">×</button>
				  <strong>Oh snap!</strong> {{$message}}
				</div>
			</div>
			@endif
		</div>

	</div>
	<script type="text/javascript">
	function dismissDialog(){
		console.log("this gets called");
		var divs = document.querySelector(".errorAlert");
		console.dir(divs);
		divs.innerHTML="";
	}
	</script>
</body>
</html>