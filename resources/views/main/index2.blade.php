@extends('main.template')

@section('content')
<style type="text/css" media="screen">
	nav .nav-wrapper form, nav .nav-wrapper form .input-field{
height: 100%;
}
</style>
<div class="ctn">
		<div class="row">
			<div class="content">
				<div ng-app="QueueApp" ng-controller="QueueCtrl as Queue">
					<div class="row">
						<div class="col s12">
							<div class="col s12 m6 l4 right">
								<nav>
									<div class="nav-wrapper blue">
							      <form>
							        <div class="input-field">
							          <input id="search" type="search" ng-model="search">
							          <label for="search"><i class="material-icons">search</i></label>
							          <i class="material-icons">close</i>
							        </div>
							      </form>
							    </div>
								</nav>
							</div>
						</div>
					</div>
					<div class="row">
					    <div class="col s12">
					        <ul tabs reload="allTabContentLoaded">
					            <li class="tab col s4">
					            	<a class="active" href="#ActiveQueues" ng-click="Queue.selectedTab(1)">Available</a>
					            </li>
					            <li class="tab col s4">
					            	<a href="#UserQueues" ng-click="Queue.selectedTab(2)">Reserved</a>
					            </li>
					            <li class="tab col s4">
					            	<a href="#PassedQueues" ng-click="Queue.selectedTab(3)">Ended</a>
					            </li>
					        </ul>
					    </div>
					    <!-- Active Queue -->
					    <div id="ActiveQueues" class="col s12">
					    	<div class="white-div ">
					    		<div ng-if="Queue.isSelected(1)">
					    			<div ng-show="loading" class="center-align"><loading></loading></div>
						    		<div ng-show="!ActiveQueues.length && !loading">
						    				<p class="flow-text center-align">NO AVAILABLE QUEUE.</p>
							    	</div>
						    		<div ng-show="ActiveQueues.length && !loading">
						    			<table class="highlight centered responsive-table">
								        <thead>
								          <tr>
														<th>Queue Name</th>
														<th>Counter</th>
														<th>Service time</th>
														<th>Service/Mins</th>
														<th>Reserve time</th>
														<th>Count</th>
														<th>Ramaining</th>
														<th>Status</th>
														@if(!Auth::guest())
															<th>Reserve</th>
								    				@endif
								          </tr>
								        </thead>
								        <tbody>
						    					<tr dir-paginate="Active in ActiveQueues | filter:search | itemsPerPage: pageSize" current-page="currentPage" pagination-id="ActiveQueues">
								    				<td> <% Active.queue_name %> </td>
								    				<td> <% Active.counter %> </td>
								    				<td> <% Queue.convertTime(Active.opentime) | date:'d MMM y HH:mm น.' %> </td>
								    				<td> <% Active.service_time %> </td>
								    				<td>
								    					<p>เริ่ม : <% Queue.convertTime(Active.start) | date:'d MMM y HH:mm น.' %></p>
								    					<p>ถึง : <% Queue.convertTime(Active.end) | date:'d MMM y HH:mm น.' %></p>
								    				</td>
								    				<td> <% Active.current_count %>/<% Active.max_count %> </td>
								    				<td>
								    					<timer countdown="Queue.countd(Active.end)"  max-time-unit="'day'" interval="1000">
																<% days %> วัน, <%hours %> ชั่วโมง <% mminutes %> นาที <% sseconds %> วินาที
															</timer>
								    				</td>
								    				<td> <% Active.status %> </td>
								    				@if(!Auth::guest())
								    				<td> <a type="button" class="btn blue wave-effect" href="{{ url('/reserve') }}/<%Active.id %>">Reserve</a> </td>
								    				@endif
						    					</tr>
						    				</tbody>
						    			</table>
						    			<br/>
											<dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html" pagination-id="ActiveQueues"></dir-pagination-controls>
						    		</div>
					    		</div>
								</div>
					    </div>
					   <!--  User Queue -->
					    <div id="UserQueues" class="col s12 ">
					    	<div class="white-div ">
					    		<div ng-if="Queue.isSelected(2)">
						    		@if(Auth::guest())
						    			<p class="flow-text center-align">AUTHENTICATION REQUIRED.</p>
						    		@else
						    			<div ng-show="loading" class="center-align"><loading></loading></div>
						    			<div ng-show="!UserQueues.length && !loading">
						    				<p class="flow-text center-align">NO RESERVED QUEUE.</p>
							    		</div>
							    		<div ng-show="UserQueues.length && !loading">
							    			<table class="highlight centered responsive-table">
									        <thead>
									          <tr>
																<th>Queue Name</th>
																<th>Service Time</th>
																<th>Service/Mins</th>
																<th>Queue Time</th>
																<th>Within</th>
																<th>Verify Key</th>
									          </tr>
									        </thead>
									        <tbody>
									    			<tr dir-paginate="UserQueue in UserQueues | filter:search | itemsPerPage: pageSize" current-page="currentPage" pagination-id="UserQueues">
									    				<td> <% UserQueue.mainqueue[0].queue_name %> </td>
									    				<td> <% Queue.convertTime(UserQueue.mainqueue[0].opentime) | date:'d MMM y HH:mm น.' %> </td>
									    				<td> <% UserQueue.mainqueue[0].service_time %> </td>
									    				<td>
									    					<% Queue.convertTime(UserQueue.queue_time) | date:'d MMM y HH:mm น.' %>
									    				</td>
									    				<td>
									    					<timer countdown="Queue.countd(UserQueue.queue_time)"  max-time-unit="'day'" interval="1000">
																	<% days %> วัน, <%hours %> ชั่วโมง <% mminutes %> นาที <% sseconds %> วินาที
																</timer>
															</td>
									    				<td>
									    					<a tooltipped class="btn red lighten-2" data-position="right" data-delay="50" data-tooltip="<% UserQueue.captcha %>">SHOW</a>
									    				</td>
									    			</tr>
								    			</tbody>
							    			</table>
							    			<br/>
												<dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html" pagination-id="UserQueues"></dir-pagination-controls>
							    		</div>
						    		@endif
					    		</div>
								</div>
					    </div>
					    <!-- Passed Queue -->
					    <div id="PassedQueues" class="col s12 ">
					    	<div class="white-div ">
					    		<div ng-if="Queue.isSelected(3)">
					    			<div ng-show="loading" class="center-align"><loading></loading></div>
						    		<div ng-show="!PassedQueues.length && !loading">
						    			<p class="flow-text center-align">NO PASSED QUEUE.</p>
							    	</div>
						    		<div ng-show="PassedQueues.length && !loading" class="center-align">
						    			<table class="highlight centered responsive-table">
								        <thead>
								          <tr>
															<th>Queue Name</th>
															<th>Service Time</th>
															<th>Service/Mins</th>
															<th>Queue Time</th>
															<th>Count</th>
															<th>Status</th>
								          </tr>
								        </thead>
								        <tbody>
								    			<tr dir-paginate="Passed in PassedQueues | filter:search | itemsPerPage: pageSize" current-page="currentPage" pagination-id="PassedQueues">
								    				<td> <% Passed.queue_name %> </td>
								    				<td> <% Queue.convertTime(Passed.opentime) | date:'d MMM y HH:mm น.' %> </td>
								    				<td> <% Passed.service_time %> </td>
								    				<td>
								    					<% Queue.convertTime(Passed.start) | date:'d MMM y HH:mm น.' %> -
								    					<% Queue.convertTime(Passed.end) | date:'d MMM y HH:mm น.' %>
								    				</td>
								    				<td> <% Passed.current_count %> </td>
								    				<td> <% Passed.status %> </td>
								    			</tr>
							    			</tbody>
						    			</table>
						    			<br/>
											<dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html" pagination-id="PassedQueues"></dir-pagination-controls>
						    		</div>
					    		</div>
								</div>
					    </div>
					</div>
				 <!-- User's Queue -->
					
				 <!-- Active Queue -->
				 
				</div>
			</div>
		</div>
	</div>

@endsection

@section('js')
	<script>
	(function()
	{

		// Main Controller

		angular
			.module('QueueApp', ['ui.materialize', 'Queue', 'User', 'ngAnimate', 'angularUtils.directives.dirPagination', 'ngLocale', 'timer'],setInterpolate)
			.controller('QueueCtrl', ['$scope', 'mainService', 'userService', QueueCtrl])
			.constant("CSRF_TOKEN", '{{ csrf_token() }}')
			.filter('range', rangeFilter)
			.directive('loading', LoadingDirective)

			function LoadingDirective() {
	      return {
	        restrict: 'E',
	        replace:true,
	        template: '<div class="preloader-wrapper big active"><div class="spinner-layer spinner-blue"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div>',
	        link: function (scope, element, attr) {
	              scope.$watch('loading', function (val) {
	                  if (val)
	                      $(element).show();
	                  else
	                      $(element).hide();
	              });
	        }
	      }
			 }
			function setInterpolate($interpolateProvider)
			{
				$interpolateProvider.startSymbol('<%');
				$interpolateProvider.endSymbol('%>');
			}

			function QueueCtrl($scope,mainService,userService)
			{

				// Pagination
				// 
				$scope.currentPage = 1;
				$scope.pageSize = 10;
				$scope.sort = 'end';
				$scope.reverse = false;

				// Variable
				// 
				$scope.tab = 1;
				$scope.loading = false;

				// Service
				// 
				this.selectedTab = function(tab)
				{
					$scope.tab = tab;
					switch (tab)
					{
						case 1: this.getActiveQueues(); break;
						case 2: this.getUserQueues(); break;
						case 3: this.getPassedQueues(); break;
					}
				}

				this.isSelected = function(tab)
				{
					return $scope.tab === tab;
				}

				this.getUserQueues = function()
				{
					$scope.loading = true;
					userService.getUserQueues()
					.then(function(data){
						$scope.UserQueues = data.result;
						$scope.loading = false;
					})
				}
				
				this.getActiveQueues = function()
				{
					$scope.loading = true;
					mainService.getActiveQueues()
						.then(function(data){
							$scope.ActiveQueues = data.result;
							$scope.loading = false;
						})
				}

				this.getPassedQueues = function()
				{
					$scope.loading = true;
					mainService.getPassedQueues()
						.then(function(data){
							$scope.PassedQueues = data.result;
							$scope.loading = false;
						})
				}

				this.reserveQueue = function(id)
				{
					console.log(id);
				}

				// First declaration
				this.selectedTab(1);
				

				// Local function
				// 
				this.sortBy = function(propertie)
				{
					$scope.reverse = ($scope.sort === propertie) ? !$scope.reverse : false;
					$scope.sort = propertie;
				}

				this.convertTime = function(time)
				{
					var date = new Date(time);
					return date;
				}

				this.countd = function(end){
					var date = new Date();
					var end = new Date(end);
					var diff = (end.getTime() / 1000) - (date.getTime() / 1000);
					return (diff<=0)?0:diff;
				}

			}

			function rangeFilter(){
				return function(input, total) 
				{
					total = parseInt(total);

					for (var i = 0; i < total; i++) 
					{
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

				var getActiveQueues = function()
				{

					var request = $http.get("App/getActiveQueues");
					return( request.then( handleSuccess, handleError ) );
				}

				var getPassedQueues = function()
				{

					var request = $http.get("App/getPassedQueues");
					return( request.then( handleSuccess, handleError ) );
				}



				return {
					getActiveQueues : getActiveQueues,
					getPassedQueues : getPassedQueues,
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
			.factory('userService', ['$http','$q', UserService]);

			function UserService($http)
			{
				var getUserQueues = function()
				{

					var request = $http.get("User/getQueues");
					return( request.then( handleSuccess, handleError ) );
				}

				return {
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

	})();

</script>

@endsection