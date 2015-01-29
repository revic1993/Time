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
	$scope.errors = {
		isError :false,
		msg : "",
	}


	$scope.fetchTags = function(){
		var getUrl = root.postUrl+"/tags/"+root.id;
		$http.get(getUrl).
			  success(function(data, status, headers, config) {
		    		root.tagList = data;		    	
		    		var temp = {
		    			color:"#18bc9c",		    			
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
						date: root.date,
						count:root.counter
					}
			};
			// console.dir(postData);
			$http.post(root.postUrl+"/dashboard/dates",postData).
			  	success(function(data, status, headers, config) {		  		  
		  		  	root.timeList.forEach(function(time){
		  		  		time.isSaved = time.isTag;
		  		  	});	 
		  		  	console.log(data); 
 		    	}).error(function(data, status, headers, config) {
 					console.log(data);
 				});

	}

	$scope.fetchDetails = function(value){
		var getUrl = root.postUrl+"/dashboard/date/"+root.id+"/"+value;
		root.timeList = new Array();
		$http.get(getUrl).
			  success(function(data, status, headers, config) {		  		
		  		root.timeList = data.data;		  		  	
		  		root.counter = data.count;
		  		console.log(data);
 		    }).error(function(data, status, headers, config) {
 					root.errors.isError =true;
 					root.errors.msg = data.msg;
 		});
	};

	$scope.removeError = function(){
		root.errors.isError =false;
		root.errors.msg = "";
	};


	$scope.fetchTags();
}]);

function dragOver(e)
{
	e.preventDefault();
	var ind = e.toElement.parentElement.dataset.index;
	var tag = current.scope.timeList[ind-1];
	var currTag = current.currentTag;
	// console.log(tag.index + " " +current.currentTag.tagName);
	if(!tag.isTag&&tag.tagColor==current.initialColor&&current.currentTag.tagName!="empty"){
		    current.scope.$apply(function(){
			tag.isTag =true;
			tag.tagColor = currTag.tagColor;
			tag.tag = currTag.tagName;			
			current.scope.counter++;	
		});
		
	}else if(tag.isTag&&current.currentTag.tagName=="empty"){
		    current.scope.$apply(function(){
			tag.isTag =false;
			tag.tagColor = current.initialColor;
			tag.tag = "";	
			current.scope.counter--;	
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