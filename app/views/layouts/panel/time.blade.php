@extends("layouts.panel.master")

@section("username")
{{$username}}
@stop


@section("container")
	<div ng-app="TimeApp" ng-controller="TimeController" id="scope">
		<div class="hidden">
				<span id="user_id">{{$id}}</span>
				<span id="url">{{URL::to("/tags")}}</span>
		</div>
		<div class="right">	
			<div class="box" style="background:((tag.color));" data-tag="((tag.tagName))" data-color="((tag.color))" draggable="true" ondragstart="startDrag(this,event)" ng-repeat="tag in tagList" onD>
				<i class="fa fa-tag"></i>&nbsp;&nbsp;((tag.tagName))
			</div>
		</div>
		<div class="col-lg-5 form-horizontal">
			<label for="inputEmail" class="col-lg-3 control-label">Date :</label>
			<div class="col-lg-9">
			  <input type="text" class="form-control" id="datepicker" placeholder="Pick a date"/>
			</div>
		</div>
		<table class="table table-striped table-hover widthControl">
		  <thead>
		    <tr>
		      <th style="text-align:center">Duration</th>
		    </tr>
		  </thead>
		  <tbody>
		    <tr ng-repeat="time in timeList" data-index = "((time.index))">
		      <td style="background:((time.tagColor))" ondragover = "dragOver(event)">((time.from +" " + time.ftZone)) - ((time.to+" "+time.ttZone))</td>
		   	  <br>
		    </tr>
		  </tbody>
		</table>
	</div>
@stop

@section("extrascripts")
{{HTML::script("js/angular.js")}}
{{HTML::script("js/time.js")}}
{{HTML::script("js/bootstrap-datetimepicker.js")}}
<script type="text/javascript">
	$('#datepicker').datepicker({
  	format: 'yyyy-mm-dd',
  	autoclose:true,
  	todayHighlight:true,
}).on("changeDate", function(e){
      	changeScopeVal(e.target.value) ;
    });
</script>
@stop
