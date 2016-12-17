
<div ng-app="QueueApp" ng-controller="QueueCtrl as Queue">
 <!-- User's Queue -->
	
 <!-- Active Queue -->
	
 <!-- Passed Queue -->
 <li ng-repeat="Passed in PassedQueues">{{ Passed.id }}</li>

</div>

<script>
	(function()
	{

		// Main Controller

		angular
			.module('QueueApp', ['ui.materialize', 'Queue', 'User'])
			.controller('QueueCtrl', ['$scope', 'mainService', 'userService', QueueCtrl])
			.constant("CSRF_TOKEN", '{{ csrf_token() }}')
			.filter('range', rangeFilter);

			function QueueCtrl($scope,mainService,userService)
			{
				$scope.currentPage = 1;
				mainService.getUserQueues()
					.then(function(data){
						$scope.UserQueues = data.result;
					})

				mainService.getActiveQueues()
					.then(function(data){
						$scope.ActiveQueues = data.result;
					})

				mainService.getPassedQueues()
					.then(function(data){
						$scope.PassedQueues = data.result;
					})
				
				function changePage(page){
					$scope.currentPage = page;
				}
			}

			function rangeFilter(){
				return function(input, total) {
					total = parseInt(total);

					for (var i = 0; i < total; i++) {
							input.push(i);
					}

					return input;
				};
			}

		// Queue Service

		angular
			.module('Queue', [])
			.factory('mainService', ['$http','$q', MainService]);

			function MainService($http,$q)
			{

				var getActiveQueues = function(){

					var request = $http.get("App/getActiveQueues");
					return( request.then( handleSuccess, handleError ) );
				}

				var getPassedQueues = function(){

					var request = $http.get("App/getPassedQueues");
					return( request.then( handleSuccess, handleError ) );
				}

				var getUserQueues = function(){

					var request = $http.get("User/getQueues");
					return( request.then( handleSuccess, handleError ) );
				}

				return {
					getActiveQueues : getActiveQueues,
					getPassedQueues : getPassedQueues,
					getUserQueues : getUserQueues,
				}

				function handleError( response ) 
				{
					return( $q.reject( response.data.status ) );
				}

				function handleSuccess( response ) 
				{
        	return( response.data );
        }
			}

		// User Service

		angular
			.module('User', [])
			.factory('userService', ['$http', UserService]);

			function UserService($http)
			{
				return {

				}
			}

	})();

</script>
