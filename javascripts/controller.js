var app=angular.module('mainApp', ['ngRoute']);
app.config(function($routeProvider){
	$routeProvider
	.when('/', {
		templateUrl: 'login.html'
	})
	.when('/dashboard',{
		// resolve: {
		// 	"check": function($location, $rootScope){
		// 		if(!$rootScope.loggedIn){
		// 			$location.path('/');
		// 		}
		// 	}
		// },
		// templateUrl: 'dashboard.html'
		
		// function($location, $rootScope){
			if (true) {
				// $location.path('/');
				redirectTo:'/';

			},
			// else{
			// 	templateUrl: 'dashboard.html';
			// }
		// },
		// redirectTo:'/',
		templateUrl: 'dashboard.html'
	})
	.otherwise({
		redirectTo: '/'
	});
});

app.controller('loginCtrl', function($scope,$location,$rootScope){
	$scope.submit=function(){
		var uname=$scope.username;
		var password=$scope.password;
		//
		$rootScope.loggedIn=false;
		//
		if($scope.username=='admin' && $scope.password=='admin'){
			$rootScope.loggedIn=true;
			$location.path('/dashboard');
		}
		else{
			alert('Oh shit!');
		}
	};
});