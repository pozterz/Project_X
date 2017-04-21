@extends('main.template')

@section('content')
	
	<div ng-app="App" ng-controller="TestCtrl">
		<% q %>
	</div>

@endsection


@section('js')
	<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular-resource.min.js" type="text/javascript"></script>

<script>
	(function()
	{
		angular
			.module('App',['ngResource'],setInterpolate)
			.controller('TestCtrl', ['$scope','TestFac', TestCtrl])
			.factory('TestFac', ['$resource',function($resource){
		  	return $resource('api/test/:id',{id : "@id"},{
		  		"update": {method: "PUT"},
		  		"reviews": {'method': 'GET', 'params': {'reviews_only': "true"}, isArray: true}
		  	});
			}])

		function TestCtrl($scope,TestFac){
			$scope.q = TestFac.query()
		}

		function setInterpolate($interpolateProvider)
		{
			$interpolateProvider.startSymbol('<%');
			$interpolateProvider.endSymbol('%>');
		}
	})();
</script>

@endsection