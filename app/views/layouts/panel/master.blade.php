<!DOCTYPE html>
<html>
<head>
	
	<title>Time Management</title>
	{{HTML::style("css/master.css")}}
	{{HTML::style("css/bootstrap.css")}}
	{{HTML::style("css/fa.css")}}
  @yield("extrastyles")
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href={{URL::to("/")}}><i class="fa fa-dashboard"></i>&nbsp;&nbsp;Time Tracking</a>
    </div>
    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="download" aria-expanded="true">
            @yield("username")&nbsp;&nbsp;
            <span class="caret"></span></a>
            <ul class="dropdown-menu" aria-labelledby="download">
              <li><a href="#"><i class="fa fa-user"></i>&nbsp;&nbsp;Profile</a></li>
              <li><a href={{URL::to("dashboard/logout")}}><i class="fa fa-sign-out"></i>&nbsp;&nbsp;Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>



<div class="row">
<div class="col-lg-3">
	<aside class="row">
		  <div class="marginT">
        <ul>
           <li><a href={{URL::to("/dashboard/time")}}><i class="fa fa-clock-o"></i> &nbsp;Time</a></li>
           <li><a href={{URL::to("/tags")}}><i class="fa fa-tags"></i> &nbsp;Tags</a></li>
         </ul> 
      </div>
	</aside>	
</div>
<div class="col-lg-8 container">  
  @yield("container")
</div>
</div>
{{HTML::script("js/jquery.js")}}
{{HTML::script("js/bootstrap.js")}}
{{HTML::script("js/flatly.js")}}
@yield("extrascripts")
</body>
</html>