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
		$scope.accounts=data;
	});
}]);





