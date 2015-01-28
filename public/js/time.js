var app = angular.module('TimeApp', [], function($interpolateProvider) {
        $interpolateProvider.startSymbol('((');
        $interpolateProvider.endSymbol('))');
    });
 current = {
			initialColor : "#18BC9C",
			currentTag : "",
			scope:null
};		

app.controller("TimeController",["$scope","$http",function($scope,$http){
	current.scope = $scope;
	var root = $scope;
	$scope.timeList = new Array();
	$scope.currentTag ;
	$scope.tagList = new Array();
	$scope.postUrl = document.getElementById("url").innerHTML;
	$scope.id= document.getElementById("user_id").innerHTML;	
	$scope.counter = 0;
	$scope.date = null;

	$scope.initTime = function(start){
		console.log(start);
		for(var i=start;i<=24;i++)
		{
			var time= {
			 			from : (i%12)?i%12:12,
			 			to : ((i+1)%12)?(i+1)%12:12,
			 			ftZone:(i<12)?"a.m.":"p.m",
			 			ttZone:((i+1)<12)?"a.m.":"p.m",
			 			isTag : false,
		 	 			tag : "",
			 			tagColor : current.initialColor,
			 			index:i
			 		  }
			 root.timeList.push(time);
		}
	}



	$scope.fetchTags = function(){
		var getUrl = root.postUrl+"/tags/"+root.id;
		$http.get(getUrl).
			  success(function(data, status, headers, config) {
		    		root.tagList = data;		    	
		    		var temp = {
		    			color:"#18bc9c",
		    			tagId:0,
		    			tagName:"empty"
		    		};
		    		root.tagList.push(temp);
		    		root.currentTag = root.tagList;		    		    	   
 		    }).error(function(data, status, headers, config) {
 					console.log(data);
 		});
	};

	$scope.sendData = function(){
			postData = {
				data:{
						time: root.timeList,
						user_id: root.id,
						date: root.date
					}
			};
			$http.post(root.postUrl+"/dashboard/dates",postData).
			  success(function(data, status, headers, config) {		  		  
		  		  	console.log(data);		  
 		    }).error(function(data, status, headers, config) {
 					console.log(data);
 		});

	}

	$scope.fetchDetails = function(value){
		var getUrl = root.postUrl+"/dashboard/date/"+root.id+"/"+value;
		$http.get(getUrl).
			  success(function(data, status, headers, config) {
		  		console.log(data);
		  		 if(data.error && data.message=="empty")
		  		  {
		  		  	
		  		  	root.initTime(1);
		  		  }  
		  		  else{
		  		  		root.timeList = data.data;
		  		  		console.log(root.timeList[root.timeList.length-1].index+1);
		  		  		root.initTime(root.timeList[root.timeList.length-1].index+1);
		  		  }
 		    }).error(function(data, status, headers, config) {
 					console.log(data);
 		});
	};
	$scope.clicked = function(){
		console.log(document.getElementById("datepicker").value+" "+$scope.date);
	};
	$scope.fetchTags();
}]);

function dragOver(e)
{
	e.preventDefault();
	var ind = e.toElement.parentElement.dataset.index;
	var tag = current.scope.timeList[ind-1];
	var currTag = current.currentTag;
	if(!tag.isTag&&tag.tagColor==current.initialColor){
		    current.scope.$apply(function(){
			tag.isTag =true;
			tag.tagColor = currTag.tagColor;
			tag.tag = currTag.tagName;	
		});
		
	}else if(tag.isTag&&currTag.tagColor.toUpperCase()==current.initialColor){
		 current.scope.$apply(function(){
			tag.isTag =false;
			tag.tagColor = current.initialColor;
			tag.tag ="";	
		});
	}else if(tag.isTag){
		current.scope.$apply(function(){
			tag.isTag =true;
			tag.tagColor = currTag.tagColor;
			tag.tag = currTag.tagName;	
		});
	}
}

function startDrag(div,event){
	event.dataTransfer.setDragImage(div, 20, 20);
	current.currentTag = {
		tagName : div.dataset.tag,
		tagColor : div.dataset.color
	};
	
}


function changeScopeVal(value){
	 current.scope.$apply(function(){
	 		current.scope.date = value;
			current.scope.fetchDetails(value);
		});
}