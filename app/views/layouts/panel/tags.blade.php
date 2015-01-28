@extends("layouts.panel.master")

@section("username")
	{{$username}}
@stop

@section("container")
	<div ng-app="TagApp" ng-controller="TagController">
		<div class="hidden">
			<span id="user_id">{{$id}}</span>
			<span id="url">{{URL::to("/tags")}}</span>
		</div>
		<form class="form-horizontal" >
    		<div class="form-group">
    		  <label for="tagname" class="col-lg-2 control-label">Tag : </label>
    		  
    		  <div class="col-lg-5">
    		    <input type="text" ng-model="tagName" class="form-control" placeholder="e.g. utilized">
    		  </div>
    		  <div class="col-lg-1">
    		  	<select id="colorselector">    
    		 		<option value="#F44336"  data-color="#F44336">red</option>
    		 		<option value="#E91E63"  data-color="#E91E63">pink</option>
    		 		<option value="#9C27B0"  data-color="#9C27B0">purple</option>
    		 		<option value="#673AB7"  data-color="#673AB7">dpurple</option>
    		 		<option value="#3F51B5"  data-color="#3F51B5">indigo</option>
    		 		<option value="#2196F3"  data-color="#2196F3">blue</option>
    		 		<option value="#03A9F4"  data-color="#03A9F4">lblue</option>
    		 		<option value="#00BCD4"  data-color="#00BCD4">cyan</option>
    		 		<option value="#009688"  data-color="#009688">teal</option>
    		 		<option value="#4CAF50"  data-color="#4CAF50">green</option>
    		 		<option value="#8BC34A"  data-color="#8BC34A">lgreen</option>
    		 		<option value="#CDDC39"  data-color="#CDDC39">lime</option>
    		 		<option value="#FFEB3B"  data-color="#FFEB3B">yellow</option>
    		 		<option value="#FFC107"  data-color="#FFC107">amber</option>
    		 		<option value="#FF9800"  data-color="#FF9800">orange</option>
    		 		<option value="#9E9E9E"  data-color="#9E9E9E">grey</option>
    		 		<option value="#795548"  data-color="#795548">brown</option>
    		 		<option value="#FF5722"  data-color="#FF5722">borange</option>
    		 		<option value="#607D8B"  data-color="#607D8B"> lgrey</option>
    		 		<option value="#000000"  data-color="#000000">black</option>
    		  	</select>
    		  </div>

    		  <div class="col-lg-2">
    		  	<input type="button" ng-click="addTags()"  value="Add" class="btn btn-primary">
    		  </div>
    		</div>
		</form>

		<div class="col-lg-10" ng-show="tagList">
			<div class="tag-list" ng-show="tagList">
				<span style="background-color:((tag.color))" ng-repeat="tag in tagList">((tag.tagName))
					<i class="fa fa-remove" ng-click="removeTag(tag.tagId)"></i>
				</span> 
			</div>
		</div>
		<!-- <div class="col-lg-3"></div> -->
		<div class="col-lg-4 alert alert-dismissable alert-danger left" ng-show="errors.exists">
		  <button type="button" class="close" ng-click="alterError()">×</button>
		  <strong>Oh snap!</strong> ((errors.message))
		</div>
	</div>
@stop

@section("extrascripts")
{{HTML::script("js/angular.js")}}
{{HTML::script("js/tags.js")}}
{{HTML::script("js/bootstrap-colorselector.js")}}
<script>
    $('#colorselector').colorselector();
</script>
@stop

@section("extrastyles")
{{HTML::style("css/bootstrap-colorselector.css")}}
@stop