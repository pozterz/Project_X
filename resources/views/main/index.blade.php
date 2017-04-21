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
												<p class="flow-text center-align">NO AVAILABLE QUEUE.
													<button type="button" class="btn-floating waves-effect waves-light blue" ng-click="Queue.selectedTab(1)">
														<i class="material-icons">refresh</i>
													</button>
												</p>
										</div>
										<div ng-show="ActiveQueues.length && !loading">
										  <div class="row">
											  <div  dir-paginate="Active in ActiveQueues | filter:search | itemsPerPage: pageSize" current-page="currentPage" pagination-id="ActiveQueues">
											    <div class="col s12 m4">
											      <div class="card z-depth-2">
											        <div class="card-content">
												        <div class="card-title">
												      		<% Active.name %>
												      	</div>
											          <p>
											          	<span class="label label-info label-as-badge""><% Active.queue_type.name %></span>
											          	<span class="label label-as-badge" ng-class="Queue.StatusColor(Active.open,Active.close,Active.current,Active.max)">
												          		<% Queue.Status(Active.open,Active.close,Active.current,Active.max) %>
												          	</span>
											          </p><br/>
											          <p class="flow-text"><strong>เวลาที่เปิดจอง : </strong><br/>
											          	<% Queue.convertTime(Active.open) | date:'d MMM y HH:mm น.' %> -
											          	<% Queue.convertTime(Active.close) | date:'d MMM y HH:mm น.' %>
											          </p>
											          <p class="flow-text"><strong>จำนวน : </strong>
											          	<span class="label label-danger label-as-badge"><% Active.current %>/<% Active.max %> คน</span>
											          </p>
											           <p class="flow-text" ng-if="Queue.Status(Active.open,Active.close,Active.current,Active.max) === 'Opening'"><strong>เวลาที่เหลือ : </strong>
											          	<timer countdown="Queue.countd(Active.close)"  max-time-unit="'day'" interval="1000">
																		<% days %> วัน, <%hours %> ชั่วโมง <% mminutes %> นาที <% sseconds %> วินาที
																	</timer>
											          	</p>
											          	<p class="flow-text" ng-if="Queue.Status(Active.open,Active.close,Active.current,Active.max) === 'Waiting'"><strong>เวลาที่เหลือ : </strong>
												          	<timer countdown="Queue.countd(Active.open)"  max-time-unit="'day'" interval="1000">
																			<% days %> วัน, <%hours %> ชั่วโมง <% mminutes %> นาที <% sseconds %> วินาที
																		</timer>
											          	</p>
											          <div class="more-detail" ng-show="collapsed" ng-class="{ 'active':collapsed }">
												          <p class="flow-text"><strong>เวลาให้บริการ : </strong><br/>
												          	<% Queue.convertTime(Active.service_start) | date:'d MMM y HH:mm น.' %> - 
												          	<% Queue.convertTime(Active.service_end) | date:'d MMM y HH:mm น.' %>
												          </p>
												          <p class="flow-text"><strong>เวลาที่จองได้ : </strong>
												          	<% Active.max_minutes %> นาที
												          </p>
											          </div>
											        </div>
											        <div class="card-action center">
											        	<button type="button" class="btn green lighten-1 wave-effect" ng-click="collapsed=!collapsed">
											        		More <i class="fa " ng-class="collapsed?'fa-caret-up':'fa-caret-down'"></i> 
											        	</button>
											        	@if(!Auth::guest())
																	<a type="button" class="btn blue wave-effect" href="{{ url('User/Reserve') }}/<% Active.id %>" ng-show="Active.current < Active.max">Reserve <i class="fa fa-calendar-check-o"></i></a>
																	<a type="button" class="btn blue wave-effect disabled" ng-show="Active.current >= Active.max" disabled>Full</a>
																@endif
											        </div>
											      </div>
											    </div>
										      <div class="clearfix" ng-if="($index+1)%3==0"></div>
										    </div>
										    <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html" pagination-id="ActiveQueues"></dir-pagination-controls>
										  </div>
										</div>
									</div>
								</div>
							</div>
						 <!--  User Queue -->
							<div id="UserQueues" class="col s12 ">
								<div class="white-div ">
									<div ng-if="Queue.isSelected(2)">
										@if(Auth::guest())
											<p class="flow-text center-align">AUTHENTICATION REQUIRED.
											</p><br/>
											<p class="flow-text center-align">
												<a type="button" href="{{ url('login') }}" class="btn waves-effect waves-light blue">Login</a> OR <a type="button" href="{{ url('register') }}" class="btn waves-effect waves-light green lighten-1">Register</a>
											</p>
										@else
											<div ng-show="loading" class="center-align"><loading></loading></div>
											<div ng-show="!UserQueues.length && !loading">
												<p class="flow-text center-align">NO RESERVED QUEUE.</p>
											</div>
											<div ng-show="UserQueues.length && !loading">
												<div class="row">
													<div dir-paginate="UserQueue in UserQueues | filter:search | itemsPerPage: pageSize" current-page="currentPage" pagination-id="UserQueues">
														<div class="col s12 m4">
															<div class="card z-depth-2">
											        	<div class="card-content">
												        	<div class="card-title">
												        		<% UserQueue.mainqueue[0].name %>
												        	</div>
												        	<p>
												          	<span class="label label-info label-as-badge"">
												          		<% UserQueue.mainqueue[0].queuetype.name %>
												          	</span>
											          	</p><br/>
											          	<p class="flow-text"><strong>เวลาที่จอง : </strong><br/>
												          	<% Queue.convertTime(UserQueue.time) | date:'d MMM y HH:mm น.' %>
												          </p>
											          	<p class="flow-text"><strong>จำนวน : </strong>
												          	<% UserQueue.reserved_min %> นาที
												          </p>
												          <p class="flow-text"><strong>เคาน์เตอร์ : </strong>
												          	<% UserQueue.mainqueue[0].user.counter_id %> | <% UserQueue.mainqueue[0].user.name %>
												          </p>
												          <p class="flow-text"><strong>เหลือเวลา : </strong>
												          	<timer countdown="Queue.countd(UserQueue.time)"  max-time-unit="'day'" interval="1000">
																		<% days %> วัน, <%hours %> ชั่วโมง <% mminutes %> นาที <% sseconds %> วินาที
																		</timer>
												          </p>
												          <div class="more-detail" ng-show="collapsed" ng-class="{ 'active':collapsed }">
													          <p class="flow-text"><strong>รหัสยืนยัน : </strong><br/>
													          	<% UserQueue.captcha_key %>
													          </p>
												          </div>
												        </div>
												        <div class="card-action center">
												        	<button type="button" class="btn green lighten-1 wave-effect" ng-click="collapsed=!collapsed">
												        		VERIFY KEY <i class="fa " ng-class="collapsed?'fa-caret-up':'fa-caret-down'"></i> 
												        	</button>
												        </div>
												      </div>
														</div>
														<div class="clearfix" ng-if="($index+1)%3==0"></div>
													</div>
												<dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html" pagination-id="UserQueues"></dir-pagination-controls>
												</div>
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
										<div ng-show="RunningQueues.length && !loading">
											<div class="row">
												<div dir-paginate="Running in RunningQueues | filter:search | itemsPerPage: pageSize" current-page="currentPage" pagination-id="RunningQueues">
													<div class="col s12 m4">
														<div class="card z-depth-2">
											        <div class="card-content">
												        <div class="card-title">
												      		<% Running.name %>
												      	</div>
												      	<p>
												          	<span class="label label-info label-as-badge""><% Running.queue_type.name %></span>
											          </p><br/>
											          <p class="flow-text"><strong>เวลาให้บริการ : </strong><br/>
											          	<% Queue.convertTime(Running.service_start) | date:'d MMM y HH:mm น.' %> -
											          	<% Queue.convertTime(Running.service_end) | date:'d MMM y HH:mm น.' %>
											          </p>
											          <p class="flow-text"><strong>เคาน์เตอร์ : </strong>
											          	<% Running.user.counter_id %> | <% Running.user.name %>
											          </p>
										          	<p class="flow-text"><strong>คิวที่ : </strong>
												          	<span class="label label-danger label-as-badge">
												          		<% Queue.runningUser(Running.userqueue,$index) %>
												          	</span>
												        </p>
											          <div class="more-detail" ng-show="collapsed" ng-class="{ 'active':collapsed }">
											          	<p class="flow-text"><strong>เวลาที่จอง : </strong>
												          	<% Queue.convertTime(RunningQueues[$index].running.time) | date:'d MMM y HH:mm น.' %> 
												          </p>
												          <p class="flow-text"><strong>เวลาที่จอง : </strong>
												          	<% RunningQueues[$index].running.reserved_min?RunningQueues[$index].running.reserved_min:0 %> นาที
												          </p>
												          <p class="flow-text"><strong>สถานะ : </strong>
												          	<span class="label label-as-badge" ng-class="(RunningQueues[$index].running.isAccept == 'yes')?'label-success':'label-danger'" ng-if="RunningQueues[$index].running.isAccept">
												          		<% RunningQueues[$index].running.isAccept | uppercase %>
												          	</span>
												          </p>
												         </div>
												      </div>
												      <div class="card-action">
											        	<button type="button" class="btn green lighten-1 wave-effect" ng-click="collapsed=!collapsed">
											        		More <i class="fa " ng-class="collapsed?'fa-caret-up':'fa-caret-down'"></i> 
											        	</button>
												      </div>
													  </div>
													</div>
													<div class="clearfix" ng-if="($index+1)%3==0"></div>
												</div>
												<dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html" pagination-id="RunningQueues"></dir-pagination-controls>
											</div>
										</div>
									</div>
								</div>
							</div>
					</div>
				</div> <!-- queueapp angular -->
			</div> <!-- content -->
		</div> <!-- row -->
	</div> <!-- ctn -->

@endsection

@section('js')
	<script>
	(function()
	{

		// Main Controller

		angular
			.module('QueueApp', ['ui.materialize', 'Queue', 'User', 'ngAnimate', 'angularUtils.directives.dirPagination', 'ngLocale', 'timer','oitozero.ngSweetAlert'],setInterpolate)
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
						},function (error){
							if(error != 200){
								swal("Sorry",'An error occured please refresh page.',"error")
								$scope.loading = false;
							}
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
						//console.log(data.result);
					},function (error){
							if(error != 200){
								swal('An error occured please refresh page.',"error")
								$scope.loading = false;
							}
						})
				}
				
				this.getActiveQueues = function()
				{
					$scope.loading = true;
					mainService.getActiveQueues()
						.then(function(data){
							$scope.ActiveQueues = data.result;
							$scope.loading = false;
						},function (error){
							if(error != 200){
								this.selectedTab = 1;
								$scope.loading = false;
							}
						})
				}

				this.getRunningQueues = function()
				{
					$scope.loading = true;
					mainService.getRunningQueues()
						.then(function(data){
							$scope.RunningQueues = data.result;
							//console.log($scope.RunningQueues);
							$scope.loading = false;
						})
				}

				this.reserveQueue = function(id)
				{
					console.log(id);
				}

				// First declaration
				this.selectedTab(1);
				@if(Session::has('success'))
					Materialize.toast('{{ Session::get('success') }}',3000,'rounded');
				@endif

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

				this.Status = function(open,close,current,max)
				{
					var now = new Date().getTime();
					var open = new Date(open).getTime();
					var close = new Date(close).getTime();
					if(current >= max) return "Full";
					else if(now > open && now > close) return "Closed";
					else if(now >= open && now <= close) return "Opening";
					else if(now < open && now < close) return "Waiting";
				}

				this.StatusColor = function(open,close,current,max)
				{
					var now = new Date().getTime();
					var open = new Date(open).getTime();
					var close = new Date(close).getTime();
					if(current >= max) return "Full";
					else if(now > open && now > close) return "label-danger";
					else if(now >= open && now <= close) return "label-success";
					else if(now < open && now < close) return "label-warning";
				}

				this.runningUser = function(userInqueue,index){
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
							workmin = userInqueue[i].reserved_min;
							if(date.getTime() >= servicetime.getTime() &&  date.getTime() < servicetime.getTime()+(workmin*1000*60)){
								console.log(userInqueue[i]);
								$scope.RunningQueues[index].running = userInqueue[i];
								return i+1;
							}
							else{
								$scope.RunningQueues[index].running = nil;
							}
						}
					}
					return 0;
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

			function UserService($http,$q)
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
					return( $q.reject( response.status ) );
				}

				function handleSuccess( response ) 
				{
					return( response.data );
				}

			}

	})();

</script>

@endsection