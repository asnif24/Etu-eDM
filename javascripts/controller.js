var app=angular.module('mainApp', ['ngRoute']);
app.config(function($routeProvider){
	$routeProvider
	.when('/', {
		templateUrl: 'html/login.html'
	})
	.when('/dashboard',{
		// resolve: {
		// 	"check": function($location, $rootScope){
		// 		if(!$rootScope.loggedIn){
		// 			$location.path('/');
		// 		}
		// 	}
		// },
		templateUrl: 'html/dashboard.html'
	})
	.when('/signup',{
		templateUrl: 'html/signup.html'
	})
	.otherwise({
		redirectTo: '/'
	});
});

app.controller('loginCtrl', function($scope,$location,$rootScope){
	$scope.submit=function(){
		// var uname=$scope.username;
		// var password=$scope.password;

		// $rootScope.loggedIn=false;

		if($scope.username=='admin' && $scope.password=='admin'){
			// $rootScope.loggedIn=true;
			$location.path('/dashboard');
		}
		else{
			alert('Something wrong!');
		}
	};
});

app.controller('signupCtrl', function($scope,$location){
	$scope.submit=function(){
		$location.path('/signup');
	};
});


app.controller('registerCtrl', function($scope, $http){
	$scope.submit=function(){
		$http.post('php/writeTest.php',{
			email:$scope.email,
			username:$scope.username,
			password:$scope.password
		}).success(function(data){
			console.log("@@");
			console.log(data);
		});
	};
});
app.controller('accountCtrl', function($scope, $http){
	$http.get('php/accounts.json')
	.success(function(data){
		$scope.accounts=data;
		// email:$scope.email,
		// username:$scope.username,
		// password:$scope.password
	});
});




// for panel control
app.controller("panelCtrl", function($scope){
	$scope.tab=1;
	$scope.selectTab=function(setTab){
		this.tab=setTab;
	};
	$scope.isSelected=function(checkTab){
		return this.tab==checkTab;
	};
});

app.directive("taskEdm", function(){
	return{
		restrict:"E",
		templateUrl:"html/task_eDM.html"
	};
});

app.directive("resultEdm", function(){
	return{
		restrict:"E",
		templateUrl:"html/result_eDM.html"
	};
});

app.directive("taskSms", function(){
	return{
		restrict:"E",
		templateUrl:"html/task_SMS.html"
	};
});

app.directive("resultSms", function(){
	return{
		restrict:"E",
		templateUrl:"html/result_SMS.html"
	};
});




app.controller("edmTaskCtrl", function($scope, $http){
	$scope.submit=function(){
	//task name
		console.log($scope.edmTaskName);
	//get the file name
		$scope.fileName=document.getElementById("edmFile").value.replace(/.*[\/\\]/, '');
		console.log(document.getElementById("edmFile").value.replace(/.*[\/\\]/, ''));
		console.log($scope.fileName);
	//upload file via php
		var file_data = $("#edmFile").prop("files")[0];
		var form_data = new FormData();                  
		form_data.append("file", file_data)
		$.ajax({
			url: "./php/edmUpload.php",
			type: "POST",
			data: form_data,
			processData: false,
			contentType: false,
			success: function(data){
				console.log(data);
				console.log("WOWOWOWWOW");
			}
		});
		$scope.go();
	};
//go & go2 to guarantee the execution order
// run python code by php
	$scope.go=function(){
		console.log($scope.fileName);
		console.log($scope.edmTaskName);
		$http.post('php/edmTaskSubmit.php',{
			zip_path:$scope.fileName,			
			task_name:$scope.edmTaskName,
			company_name:'ETU'
		}).success(function(data){
			$scope.msg=data;
			console.log("edmTaskSubmit done");
			console.log($scope.msg);
			$scope.go2();
		});
	};
// push task_name & task_id into tasks.json
	$scope.go2=function(){
		if($scope.msg.status=="SUCCESS"){
                	$scope.response="The EDM task has been submitted. Please wait for the result.";
        		$http.post('php/edmTaskSubmit2.php',{
                        	task_id:$scope.msg.task_id,
                        	task_name:$scope.edmTaskName,
                        	company_id:$scope.msg.company_id,
                        	company_name:'ETU'
                	}).success(function(data2){
                        	$scope.msg2=data2;
                        	console.log(data2);
				console.log("edmTaskSubmit2 done");
                        });
			$http.post('php/edmWriteJson.php',{
				taskName: $scope.edmTaskName,
                        	taskID: $scope.msg.task_id
			}).success(function(data){
        	                console.log("write...");
                	}).error(function(data){
                        	console.log("error");
                        	console.log(data);
	                });

		}
        	else{
                	$scope.response="Please check the data format and submit the task again.";
        	}	
	};
});

app.controller("edmResultCtrl", function($scope, $http){
//for select items
	$http.get('json/edmTasks.json')
	.success(function(data){
		$scope.edmTasks=data;
		console.log($scope.edmTasks);
	});

	$scope.submit=function(){
	//get task ID
		$scope.taskID=document.getElementById("edmSelectTask").value;
	//call get_edm_report.py
		$http.post('php/edmResult.php',{
			task_id:$scope.taskID
		}).success(function(data){
			$scope.edmResult=data;
			console.log("@@");
			console.log($scope.edmResult);
		});
	};
});


app.controller("smsTaskCtrl", function($scope, $http){
	$scope.submit=function(){
		//get the file name
		$scope.fileName=document.getElementById("smsFile").value.replace(/.*[\/\\]/, '');
		console.log(document.getElementById("smsFile").value.replace(/.*[\/\\]/, ''));
		//upload file via php
		var file_data = $("#smsFile").prop("files")[0];   
		var form_data = new FormData();                  
		form_data.append("file", file_data)
		$.ajax(
	  	{
			url: "./php/smsUpload.php",
			type: "POST",
			data: form_data,
			processData: false,
			contentType: false,
			success: function(data){
				console.log(data);
			} 
		});
		$scope.go();
	};
	$scope.go=function(){
		console.log($scope.smsTaskName);
		//call csvreader.php to send sms
		$http.post('php/csvreader.php',{
			// sendfilename
			fileName: $scope.fileName,
			taskName: $scope.smsTaskName 
		}).success(function(data){
			$scope.msg=data;
			console.log($scope.msg);
		});
	};
});

app.controller("smsResultCtrl", function($scope, $http){
	$http.get('php/getTasklist.php')
	.success(function(data){
		$scope.smsTasks=data;
		//console.log($scope.smsTasks);
	});

	$scope.submit=function(){
	//get filename
		//console.log($scope.logName);
		//$scope.fileName=document.getElementById("smsSelectTask").value;
	//call txtreader.php
		//var name="aaa.txt";
		$scope.log_name=document.getElementById("smsSelectTask").value;
		//$scope.task_name=document.getElementById("smsSelectTask").taskname;
		console.log($scope.log_name);
		//console.log($scope.task_name);
		console.log(document.getElementById("smsSelectTask"));
		$http({
			method: 'POST',
			url: 'php/txtreader.php',
			data: "fileName=" + $scope.log_name,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).success(function(data){
			$scope.smsResult=data;
			//zz
			console.log("@@");
			console.log($scope.smsResult);
		}).error(function(data){
			console.log("error QQ");
		});

		//$http.post('php/txtreader.php',{
		//	'logName' : $scope.log_name,
		//	'taskName' : $scope.task_name
		//}).success(function(data){
		//	$scope.smsResult=data;
		//	//zz
		//	console.log("@@");
		//	console.log($scope.smsResult);
		//}).error(function(data){
		//	console.log("error QQ");
		//});
		
		//call get_edm_report.py
		// $http.post('php/edmResult.php',{
		// 	task_id:$scope.taskID
		// }).success(function(data){
		// 	$scope.edmResult=data;
		// 	//zz
		// 	console.log("@@");
		// 	console.log($scope.edmResult);
		// });
		//console.log($scope.fileName);
		console.log("T___T");
	};


	// $scope.go=function(){
	// 	var file_name=$scope.fileName;
	// 	// $http.post('sms/csvreader.php',{
	// 	$http.post('sms/txtreader.php',{
	// 		fileName:file_name
	// 	}).success(function(data){
	// 		$scope.smsResult=data;
	// 	});
	// }
});




