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





//python check program
app.controller("edmTaskCtrl", function($scope, $http){
	$scope.submit=function(){
		// var name=$scope.edmTaskName;
		// document.getElementById("edmTaskName").name;
		
		//task name
		console.log($scope.edmTaskName);
		//get the file name
		$scope.fileName=document.getElementById("edmFile").value.replace(/.*[\/\\]/, '');
		console.log(document.getElementById("edmFile").value.replace(/.*[\/\\]/, ''));
		//upload file via php
		var file_data = $("#edmFile").prop("files")[0];   
    	var form_data = new FormData();                  
    	form_data.append("file", file_data)
		$.ajax(
      	{
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
 		//run python code by php
		$http.post('php/edmTaskSubmit.php',{
			zip_path:$scope.zipPath,
			task_name:$scope.edmTaskName,
			company_name:'ETU'
		}).success(function(data){
			$scope.msg=data;
			//zz
			console.log("@@");
			console.log($scope.msg);
		});
		//push task_name & task_id into tasks.json
		$http.post('php/edmWriteJson.php',{
			taskName: $scope.edmTaskName,
			taskID: $scope.msg.task_id		//need try!!!
		}).success(function(data){
			console.log("write success");
		}).error(function(data){
			console.log("error");
			console.log(data);
		});

	};
});




app.controller("edmResultCtrl", function($scope, $http){
	$http.get('json/edmTasks.json')
	.success(function(data){
		$scope.edmTasks=data;
	});

	$scope.submit=function(){
		//get task ID
		$scope.taskID=document.getElementById("selectTask").value;
		//call get_edm_report.py
		$http.post('php/edmResult.php',{
			task_id:$scope.taskID
		}).success(function(data){
			$scope.edmResult=data;
			//zz
			console.log("@@");
			console.log($scope.edmResult);
		});
	}


	// $scope.go=function(){
	// 	$http.post('php/pytest.php').success(function(data){
	// 		$scope.msg=data;
	// 		console.log("@@");
	// 		console.log($scope.msg);
	// 	});
	// };
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
				console.log("WOWOWOWWOW");
			} 
		});
		
		//call csvreader.php to send sms
		$http.post('php/csvreader.php',{
			// sendfilename
			fileName: $scope.fileName
		}).success(function(data){
			// $scope.msg=JSON.parse(data);
			$scope.msg=data;
			console.log("@@");
			console.log($scope.msg);
		});
	};


});

app.controller("smsResultCtrl", function($scope, $http){
	$scope.go=function(){
		var file_name=$scope.fileName;
		// $http.post('sms/csvreader.php',{
		$http.post('sms/txtreader.php',{
			fileName:file_name
		}).success(function(data){
			$scope.smsResult=data;
		});
	}
});

app.controller("phpCtrl", function($scope, $http){
	$scope.go=function(){
		$http.post('php/pytest.php').success(function(data){
			// $scope.msg=JSON.parse(data);
			$scope.msg=data;
			console.log("@@");
			console.log($scope.msg);
		});
		// window.location.reload();
	};
});








