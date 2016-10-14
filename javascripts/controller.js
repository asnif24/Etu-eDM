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
	//
	.when('/test',{
		templateUrl: 'html/test.html'
	})
	.when('/task_eDM',{
		templateUrl: 'html/task_eDM.html'
	})
	.when('/task_SMS',{
		templateUrl: 'html/task_SMS.html'
	})
	.when('/result',{
		templateUrl: 'html/result.html'
	})
	//
	.otherwise({
		redirectTo: '/'
	});
});

app.controller('loginCtrl', function($scope,$location,$rootScope){
	$scope.submit=function(){
		// var uname=$scope.username;
		// var password=$scope.password;
		// //
		// $rootScope.loggedIn=false;
		// //
		if($scope.username=='admin' && $scope.password=='admin'){
			//$rootScope.loggedIn=true;
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

app.controller('registerCtrl',['$scope','$http', function($scope,$http){
	$scope.submit=function(){
		var accountData={
			"email": $scope.email,
			"username": $scope.username,
			"password": $scope.password
		};
		$http.post('json/accounts.json',accountData).success(function(){
			//data.push(accountData);
			$scope.msg = 'Data saved';
		});	
	};


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
}]);

app.controller('accountCtrl', ['$scope','$http', function($scope,$http){
	$http.get('json/accounts.json').success(function(data){
		console.log(data);
		$scope.accounts=data;
	});
}]);

app.controller("panelCtrl", function(){
	this.tab=1;
	this.selectTab=function(setTab){
		this.tab=setTab;
	};
	this.isSelected=function(checkTab){
		return true;//this.tab==checkTab;
	};
});
//python check program
app.controller("pyCtrl", ['$scope', '$http', function($scope, $http){
	$scope.submit=function(){
		var name=$scope.edmTaskName;
		console.log(5);
		$http.post('check_zip_format_py.py').success(function(data){
			//data.push(accountData);
			console.log(data);
			console.log(name);
			$scope.msg = 'Data saved';
			console.log(6);
		});
	}
}])





