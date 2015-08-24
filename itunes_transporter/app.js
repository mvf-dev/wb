var myApp=angular.module('myApp',[]);

myApp.controller('mainController',['$scope','$http',function($scope,$http) {


	$scope.handle='';

	$http.get('http://dm-mediainfo-2/itunes_transporter/api.php').

		success(function(result) {
			console.log(result);
			$scope.providers=result;
		})
		.error(function(data, status){
			console.log(data);

		});
}]);


