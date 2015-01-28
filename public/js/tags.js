var app = angular.module('TagApp', [], function($interpolateProvider) {
        $interpolateProvider.startSymbol('((');
        $interpolateProvider.endSymbol('))');
    });

app.controller("TagController",["$scope","$http",function($scope,$http){
	root = $scope;
	$scope.errors={
		exists:false,
		message:""
	}
	$scope.id= document.getElementById("user_id").innerHTML;	
	$scope.taglist=new Array("");
	$scope.tagName="";
	$scope.postUrl = document.getElementById("url").innerHTML;
	$scope.colorName;
	$scope.fetchTags = function(){
		var getUrl = root.postUrl+"/"+root.id;
		$http.get(getUrl).
			  success(function(data, status, headers, config) {
		    		root.tagList = data;
 		    		console.log(data);
 		    }).error(function(data, status, headers, config) {
 					console.log(data);
 		});
	};

	$scope.addTags = function(){
		var color = document.getElementById("colorselector").value;
		var newTag={
			"tagName":root.tagName,
			"id":root.id,
			"color":color
		};
		// console.dir(root.colorName);
		$http.post(root.postUrl,newTag).
		    success(function(data, status, headers, config) {
		    		root.tagList.push(data);
		    		root.tagName="";
 		    		console.log(data);
 		    }).error(function(data, status, headers, config) {
 					if(data.success==false)
 					{
 						root.errors.exists=true;
 						if(data.type=="tag")
 							root.errors.message=root.tagName + data.message;
 						else if(data.type="color")
 							root.errors.message = data.message;
 						root.tagName="";
 					}
 		});
	};

	$scope.removeTag = function(tagId){
		var deleteUrl = root.postUrl+"/"+tagId;
		$http.delete(deleteUrl).
			 success(function(data,status,headers,config){
			 	var len = root.tagList.length;
			 	for(i=0;i<len;i++)
			 	{
			 		if(root.tagList[i].tagId==tagId)
			 		{
			 			root.tagList.splice(i,1);
			 			break;
			 		}
			 	}
			 }).error(function(data,status,headers,config){
			 	console.log(data);
			 });
	};

	$scope.alterError = function(){
		root.errors.exists = false;
	}
	root.fetchTags();
}]);