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
	// .when('/signup',{
	// 	templateUrl: 'html/signup.html'
	// })
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

// app.controller('registerCtrl',['$scope','$http', function($scope,$http){
// 	$scope.submit=function(){
// 		var accountData={
// 			"email": $scope.email,
// 			"username": $scope.username,
// 			"password": $scope.password
// 		};
// 		console.log(accountData);
// 		console.log(5566);
// 		$http.post('json/accounts',JSON.stringify(accountData))
// 		.success(function(data){
// 			//data.push(accountData);
// 			console.log("success");
// 			console.log(data);
// 			$scope.msg = 'Data saved';
// 		})
// 		.error(function(data){
// 			console.log("error");
// 			console.log(data);
// 		});	
// 	};


	// var store=this;
	// //this.account={};
	// var accountData={
	// 	"email": $scope.email,
	// 	"username": $scope.username,
	// 	"password": $scope.password
	// };

	// this.addAccount=function(account){
	// 	$http.post('/json/accounts.json').then(function(data){
	// 		store.accounts.push(data);
	// 	});
	// };
// }]);

// app.controller('accountCtrl', ['$scope','$http', function($scope,$http){
// 	$http.get('json/accounts.json').success(function(data){
// 		// console.log(data);
// 		$scope.accounts=data;
// 	});
// }]);

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

app.directive("taskSms", function(){
	return{
		restrict:"E",
		templateUrl:"html/task_SMS.html"
	};
});

app.directive("taskResult", function(){
	return{
		restrict:"E",
		templateUrl:"html/result.html"
	};
});





//python check program
app.controller("pyCtrl", ['$scope', '$http', function($scope, $http){
	$scope.submit=function(){
		var name=$scope.edmTaskName;
		console.log(5);
		$http.post('json/accounts.json',"name").success(function(data){
			//data.push(accountData);
			console.log(data);
			console.log(name);
			$scope.msg = 'Data saved';
			console.log(6);
			$scope.zz=data;
		});
	}
}])





