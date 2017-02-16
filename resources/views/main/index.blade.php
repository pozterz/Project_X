@extends('main.template')

@section('content')
<style type="text/css" media="screen">
	nav .nav-wrapper form, nav .nav-wrapper form .input-field{
height: 100%;
}
.white-space-pre-line {
    white-space: pre-line;
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
									<div class="nav-wrapper pink lighten-2">
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
					            	<a class="active" href="#ActiveQueues" ng-click="Queue.selectedTab(1)" tooltipped data-position="top" data-delay="500" data-tooltip="This panel showing ALL AVAILABLE QUEUES and user can reserve the queue here.">Available</a>
					            </li>
					            <li class="tab col s4">
					            	<a href="#UserQueues" ng-click="Queue.selectedTab(2)" tooltipped data-position="top" data-delay="500" data-tooltip="This panel showing all User's RESERVED QUEUES and user can get information about your reserved queue here.">Reserved</a>
					            </li>
					            <li class="tab col s4">
					            	<a href="#RunningQueues" ng-click="Queue.selectedTab(3)" tooltipped data-position="top" data-delay="500" data-tooltip="This panel showing all User's RUNNING QUEUES.">Running</a>
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
														<th>Name</th>
														<th>Type</th>
														<th>Working time</th>
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
								    				<td> <% Active.name %> </td>
								    				<td> <% Active.queue_type.name %> </td>
								    				<td> <% Queue.convertTime(Active.workingtime) | date:'d MMM y HH:mm น.' %> </td>
								    				<td>
								    					<p>เริ่ม : <% Queue.convertTime(Active.open) | date:'d MMM y HH:mm น.' %></p>
								    					<p>ถึง : <% Queue.convertTime(Active.close) | date:'d MMM y HH:mm น.' %></p>
								    				</td>
								    				<td> <% Active.current %>/<% Active.max %> </td>
								    				<td>
								    					<timer countdown="Queue.countd(Active.close)"  max-time-unit="'day'" interval="1000">
																<% days %> วัน, <%hours %> ชั่วโมง <% mminutes %> นาที <% sseconds %> วินาที
															</timer>
								    				</td>
								    				<td> <% Queue.Status(Active.open,Active.close) %> </td>
								    				@if(!Auth::guest())
								    				<td> <a type="button" class="btn blue wave-effect" href="{{ url('User/Reserve') }}/<% Active.id %>">Reserve</a> </td>
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
															<th>Name</th>
															<th>Working time</th>
															<th>Avg. time</th>
															<th>Queue time</th>
															<th>Remaining</th>
															<th>Status</th>
															<th>Verify Key</th>
									          </tr>
									        </thead>
									        <tbody>
									    			<tr dir-paginate="UserQueue in UserQueues | filter:search | itemsPerPage: pageSize" current-page="currentPage" pagination-id="UserQueues">
									    				<td> <% UserQueue.mainqueue[0].name %>(<% UserQueue.mainqueue[0].queuetype.name %>) </td>
									    				<td> <% Queue.convertTime(UserQueue.mainqueue[0].workingtime) | date:'d MMM y HH:mm น.' %> </td>
									    				<td> <% UserQueue.mainqueue[0].workmin %> </td>
									    				<td>
									    					<% Queue.convertTime(UserQueue.time) | date:'d MMM y HH:mm น.' %>
									    				</td>
									    				<td>
									    					<timer countdown="Queue.countd(UserQueue.time)"  max-time-unit="'day'" interval="1000">
																	<% days %> วัน, <%hours %> ชั่วโมง <% mminutes %> นาที <% sseconds %> วินาที
																</timer>
															</td>
																<td>
									    					<% UserQueue.isAccept | uppercase %>
									    				</td>
									    				<td>
									    					<a tooltipped class="btn red lighten-2" data-position="right" data-delay="50" data-tooltip="<% UserQueue.captcha_key %>">SHOW</a>
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
					    <!-- Running Queue -->
					    <div id="RunningQueues" class="col s12 ">
					    	<div class="white-div ">
					    		<div ng-if="Queue.isSelected(3)">
					    			<div ng-show="loading" class="center-align"><loading></loading></div>
						    		<div ng-show="!RunningQueues.length && !loading">
						    			<p class="flow-text center-align">NO RUNNING QUEUE.</p>
							    	</div>
						    		<div ng-show="RunningQueues.length && !loading" class="center-align">
						    			<table class="highlight centered responsive-table">
								        <thead>
								          <tr>
															<th>Queue Name</th>
															<th>Working Time</th>
															<th>Service/Mins</th>
															<th>User</th>
															<th>Start time</th>
															<th>Status</th>
								          </tr>
								        </thead>
								        <tbody>
								    			<tr dir-paginate="Running in RunningQueues | filter:search | itemsPerPage: pageSize" current-page="currentPage" pagination-id="RunningQueues">
								    				<td> <% Running.name %> </td>
								    				<td> <% Queue.convertTime(Running.workingtime) | date:'d MMM y HH:mm น.' %> </td>
								    				<td> <% Running.workmin %> </td>
								    				<td>
								    					<% Queue.runningUser(Running.userqueue,Running.workmin,$index) %>
								    					<% Running.running.user.name %>
								    				</td>
								    				<td> <% Queue.convertTime(Running.running.time)  | date:'d MMM y HH:mm น.' %> </td>
								    				<td> <% Running.running.isAccept | uppercase %> </td>
								    			</tr>
							    			</tbody>
						    			</table>
						    			<br/>
											<dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html" pagination-id="RunningQueues"></dir-pagination-controls>
						    		</div>
					    		</div>
								</div>
					    </div>
					</div>
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
						case 3: this.getRunningQueues(); break;
					}
				}

				this.isSelected = function(tab)
				{
					return $scope.tab === tab;
				}

				this.getUserQueues = function()
				{
					$scope.loading = true;
					@if(!Auth::guest())
						userService.getUserQueues()
						.then(function(data){
							$scope.UserQueues = data.result;
							$scope.loading = false;
						})
					@else
						$scope.UserQueues = [];
						$scope.loading = false;
					@endif
				}

				this.getQueue = function(id)
				{
					$scope.loading = true;
					mainService.getQueue(id)
					.then(function(data){
						$scope.QueueData = data.result;
						$scope.loading = false;
						console.log(data.result);
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

				this.getRunningQueues = function()
				{
					$scope.loading = true;
					mainService.getRunningQueues()
						.then(function(data){
							$scope.RunningQueues = data.result;
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

				this.Status = function(open,close)
        {
        	var now = new Date().getTime();
        	var open = new Date(open).getTime();
        	var close = new Date(close).getTime();
        	if(now > open && now > close) return "Closed";
        	else if(now >= open && now <= close) return "Opening";
        	else if(now < open && now < close) return "Waiting";
        }

        this.runningUser = function(userInqueue,workmin,index){
        	var date = new Date();
        	var nil = {
        		user : { name : "-"},
        		time : "-",
        		isAccept : "-"
        	};
        	if(userInqueue.length == 0) $scope.RunningQueues[index].running = nil;
        	else{
	        	for(var i = 0; i < userInqueue.length; i++){
	        		servicetime = new Date(userInqueue[i].time);
	        		if(date.getTime() >= servicetime.getTime() &&  date.getTime() < servicetime.getTime()+(workmin*1000*60)){
	        			$scope.RunningQueues[index].running = userInqueue[i];
	        		}
	        		else{
	        			$scope.RunningQueues[index].running = nil;
	        		}
	        	}
        	}
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

				var getRunningQueues = function()
				{

					var request = $http.get("App/getRunningQueues");
					return( request.then( handleSuccess, handleError ) );
				}

				var getQueue = function(id)
				{

					var request = $http.get("App/getQueue/"+id);
					return( request.then( handleSuccess, handleError ) );
				}

				return {
					getActiveQueues : getActiveQueues,
					getRunningQueues : getRunningQueues,
					getQueue : getQueue,
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