@extends('main.template')

@section('content')
<div class="content" ng-app="AdminApp" ng-controller="QueueAdminCtrl as QueueAdmin">
	<div class="">
		<div class="row card-panel">
			<div class="col s12 m12 l12">
				<div class="col s12 m4 l4" ng-click="QueueAdmin.selectedTab(1)">
					<div class="grow card-panel z-depth-2 red lighten-1">
						<div class="card-content white-text">
							<p class="flow-text"><i class="fa fa-users"></i>  Manage Users.</p>
						</div>
					</div>
				</div>
				<div class="col s12 m4 l4" ng-click="QueueAdmin.selectedTab(2)">
					<div class="grow card-panel z-depth-2 orange lighten-1">
						<div class="card-content white-text">
							<p class="flow-text"><i class="fa fa-calendar"></i>  Manage Queues.</p>
						</div>
					</div>
				</div>
				<div class="col s12 m4 l4" ng-click="QueueAdmin.selectedTab(3)">
					<div class="grow card-panel z-depth-2 blue lighten-1">
						<div class="card-content white-text">
							<p class="flow-text"><i class="fa fa-bar-chart"></i>  .</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row card-panel">
		<div class="row">
	    <div class="input-field col s12 m4 l4">
	    	<i class="material-icons prefix">search</i>
	      <input id="search" type="search" ng-model="search">
	      <label for="search">Search</label>
	    </div>
	    <div class="input-field col s12 m4 l4">
	    </div>
	    <div class="input-field col s12 m4 l4" ng-show="QueueAdmin.isSelected(2)">
	    	<button class="btn blue right" data-target='newqueueModal' modal ready="QueueAdmin.newQueueinit()"><i class="fa fa-plus"></i> NEW QUEUE</button>
	    </div>
    </div>
		<div ng-show="loading" class="center-align"><br/><br/><loading></loading><br/><br/></div>
		<div class="row" ng-show="QueueAdmin.isSelected(1) && !loading">
			<table class="highlight centered responsive-table">
        <thead>
          <tr>
						<th data-field="name">Name</th>
						<th data-field="username">Username</th>
						<th data-field="profile">Profile</th>
						<th data-field="Reserved">Reserved</th>
						<th data-field="history">History</th>
						<th data-field="delete">Delete</th>
          </tr>
        </thead>
        <tbody>
        	<tr dir-paginate="User in Users | filter:search | itemsPerPage: pageSize" current-page="currentPage" pagination-id="Users">
        		<td> <% User.name %> </td>
        		<td> <% User.username %> </td>
        		<td>
							<a class="btn-floating waves-effect waves-light btn" data-target='userModal' modal ready="QueueAdmin.getUser(User.id)" complete="QueueAdmin.completeModal()"><i class="fa fa-user"></i></a>
						</td>
						<td>
							<a class="btn-floating waves-effect waves-light orange btn" data-target='reservedModal'  modal ready="QueueAdmin.getUserReserved(User.id)" complete="QueueAdmin.completeModal()"><i class="fa fa-calendar-check-o"></i></a>
						</td>
						<td>
							<a class="btn-floating waves-effect waves-light blue btn" data-target='historyModal' modal ready="QueueAdmin.getUserHistory(User.id)" complete="QueueAdmin.completeModal()"><i class="fa fa-history"></i></a>
						</td>
						<td>
							<a class="btn-floating waves-effect waves-light red btn" onclick="return confirm('Confirm delete ?')" ng-click="QueueAdmin.deleteUser(User.id)">
								<i class="fa fa-close"></i>
							</a>
						</td>
        	</tr>
        </tbody>
      </table>
      <br/>
			<dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html" pagination-id="Users"></dir-pagination-controls>
		</div>
		<div class="row" ng-show="QueueAdmin.isSelected(2) && !loading">
			<table class="highlight centered responsive-table">
        <thead>
          <tr>
						<th>Name</th>
						<th>Type</th>
						<th>Working time</th>
						<th>Reserve time</th>
						<th>Count</th>
						<th>Ramaining</th>
						<th>Detail</th>
						<th>List</th>
						<th>Delete</th>
          </tr>
        </thead>
        <tbody>
					<tr dir-paginate="Queue in Queues | filter:search | itemsPerPage: pageSize" current-page="currentPage" pagination-id="Queues">
  					<td> <% Queue.name %> </td>
    				<td> <% Queue.queue_type.name %> </td>
    				<td> <% QueueAdmin.convertTime(Queue.workingtime) | date:'d MMM y HH:mm น.' %> </td>
    				<td>
    					<p>เริ่ม : <% QueueAdmin.convertTime(Queue.open) | date:'d MMM y HH:mm น.' %></p>
    					<p>ถึง : <% QueueAdmin.convertTime(Queue.close) | date:'d MMM y HH:mm น.' %></p>
    				</td>
    				<td> <% Queue.current %>/<% Queue.max %> </td>
    				<td>
    					<timer countdown="QueueAdmin.countd(Queue.close)"  max-time-unit="'day'" interval="1000">
								<% days %> วัน, <%hours %> ชั่วโมง <% mminutes %> นาที <% sseconds %> วินาที
							</timer>
    				</td>
    				<td>
							<button class="btn-floating waves-effect waves-light btn" data-target='queueModal' modal ready="QueueAdmin.getQueue(Queue.id)" complete="QueueAdmin.completeModal()"><i class="fa fa-info"></i></button>
						</td>
						<td>
							<a class="btn-floating waves-effect waves-light orange btn" data-target='userListModal' modal ready="QueueAdmin.getUserInQueue(Queue.id)" complete="QueueAdmin.completeModal()"><i class="fa fa-check-circle"></i></a>
						</td>
						<td>
							<a class="btn-floating waves-effect waves-light red btn" onclick="return confirm('Confirm delete ?')">
								<i class="fa fa-close"></i>
							</a>
						</td>
					</tr>
				</tbody>
			</table>
			<br/>
			<dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html" pagination-id="Queues"></dir-pagination-controls>
		</div>
	</div>
	<!-- usermodal -->
	<div id="userModal" class="modal">
    <div class="modal-content">
    	<div ng-show="innerloading" class="center-align"><br/><br/><innerloading></innerloading><br/><br/></div>
    	<div ng-show="!innerloading">
	      <ul class="collection with-header">
					<li class="collection-item pink-border">
						<h4>
							<span class="card-title flow-text">
								<i class="fa fa-user"></i> <% User.name %>
							</span>
						</h4>
					</li>
					<li class="collection-item blue-border">
						Username : <% User.username %>
					</li>
					<li class="collection-item blue-border">
						E-mail : <% User.email %>
					</li>
					<li class="collection-item blue-border">
						Phone : <% User.Phone %>
					</li>
					<li class="collection-item blue-border">
						Last Active : <% QueueAdmin.convertTime(User.updated_at) | date:'d MMM y เวลา HH:mm น.' %>
					</li>
				</ul>
			</div>
    </div>
    <div class="modal-footer">
        <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
	</div>
	<!-- new queue modal -->
	<div id="newqueueModal" class="modal">
		<div class="modal-content" ng-show="addQueueStatus == 'Success'">
			<div class="flow-text">
				ADDED NEW QUEUE SUCCESSFULLY.
    	</div>
		</div>
    <div class="modal-content" ng-hide="addQueueStatus == 'Success'">
    	<div class="row">
				<div class="input-field col s12">
					<input id="queue_name" type="text" name="queue_name" class="validate" ng-model="newQueue.name" length="150" ng-class="newQueueResult.name.length?'invalid':''">
						<label for="queue_name" ng-if="newQueueResult.name.length" data-error="<% newQueueResult.name[0] %>">Queue name</label>
						<label for="queue_name" ng-if="!newQueueResult.name.length" data-error="Please input 6 charactor or more" data-success="Validated">Queue Name</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s12" ng-show="queuetype.length">
				  <select ng-model="newQueue.queuetype_id" material-select watch>
				    <option ng-repeat="type in queuetype" value="<% type.id %>"><% type.name %></option>
				  </select>
				  <label>Queue type</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s12">
					<input id="counter" type="text" name="counter" class="validate" ng-model="newQueue.counter" length="100" ng-class="newQueueResult.name.length?'invalid':''">
						<label for="counter" ng-if="newQueueResult.counter.length" data-error="<% newQueueResult.counter[0] %>">Counter</label>
						<label for="counter" ng-if="!newQueueResult.counter.length" data-error="Please input 6 charactor or more" data-success="Validated">Counter</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s6">
					<input id="workmin" type="number" name="workmin" class="validate" ng-model="newQueue.workmin" ng-class="newQueueResult.name.length?'invalid':''">
						<label for="workmin" ng-if="newQueueResult.workmin.length" data-error="<% newQueueResult.workmin[0] %>">Service Time (minute)</label>
						<label for="workmin" ng-if="!newQueueResult.workmin.length" data-error="Please input 6 charactor or more" data-success="Validated">Service Time (minute)</label>
				</div>
				<div class="input-field col s6">
					<input id="max" type="number" name="max" class="validate" ng-model="newQueue.max" ng-class="newQueueResult.max.length?'invalid':''">
						<label for="max" ng-if="newQueueResult.max.length" data-error="<% newQueueResult.max[0] %>"">Max Queue</label>
						<label for="max" ng-if="!newQueueResult.max.length" data-error="Please input 6 charactor or more" data-success="Validated">Max Queue</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s6">
					<input type="text" name="workingtime" input-date ng-model="newQueue.workingtime" ng-class="newQueueResult.workingtime.length?'invalid':''">
						<label for="workingtime" ng-if="newQueueResult.workingtime.length" data-error="<% newQueueResult.workingtime[0] %>">Open Date</label>
						<label for="workingtime" ng-if="!newQueueResult.workingtime.length">Open Date</label>
				</div>
				<div class="input-field col s6">
					<input id="open_timepicker" type="time" name="workingtime_time" ng-model="newQueue.workingtime_time" input-clock data-twelvehour="false">
				</div>
			</div>
			<div class="row">
				<div class="input-field col s6">
					<input id="open" type="text" name="open" input-date ng-model="newQueue.open" ng-class="newQueueResult.open.length?'invalid':''">
						<label for="open" ng-if="newQueueResult.open.length" data-error="<% newQueueResult.open[0] %>">Start Date</label>
						<label for="open" ng-if="!newQueueResult.open.length">Start Date</label>
				</div>
				<div class="input-field col s6">
					<input id="start_timepicker" type="time" name="open_time" ng-model="newQueue.open_time" input-clock data-twelvehour="false">
				</div>
				
			</div>
			<div class="row">
				<div class="input-field col s6">
					<input id="close" type="text" name="close" input-date ng-model="newQueue.close" ng-class="newQueueResult.close.length?'invalid':''">
						<label for="close" ng-if="newQueueResult.close.length" data-error="<% newQueueResult.close[0] %>">Close Date</label>
						<label for="close" ng-if="!newQueueResult.close.length">Close Date</label>
				</div>
				<div class="input-field col s6">
					<input id="close_timepicker" type="time" name="end_time" ng-model="newQueue.close_time" input-clock data-twelvehour="false">
				</div>
			</div>
			<div class="row center">
				<button type="button" ng-click="QueueAdmin.NewQueue(newQueue)" class="btn waves-effect waves-light blue"><i class="fa fa-check-circle"></i> Add</button>
			</div>
    </div>
    <div class="modal-footer">
        <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
	</div>
	<!-- queue modal -->
	<div id="queueModal" class="modal">
    <div class="modal-content">
    	<div ng-show="innerloading" class="center-align"><br/><br/><innerloading></innerloading><br/><br/></div>
    	<div ng-show="!singleQueue.length && !innerloading">
    		<p class="flow-text center-align">NO THIS QUEUE.</p>
    	</div>
    	<div ng-show="singleQueue.length && !innerloading">
	      <div class="col s12 m12 l12" ng-repeat="q in singleQueue">
					<div class="card-panel">
						<ul class="collection with-header">
							<li class="collection-header red-border">				
								<h5 class="flow-text"><i class="fa fa-hashtag"></i> <strong>Queue name</strong> : <% q.name %>
								<br>
							</li>
							<li class="collection-item blue-border">
								<strong>Type</strong> : <% q.queue_type.name %>
							</li>
							<li class="collection-item blue-border white-space-pre-line">
								<strong>Requirement</strong> : <% q.queue_type.requirement %>
							</li>
							<li class="collection-item blue-border white-space-pre-line">
								<strong>Document</strong> : <% q.queue_type.document %>
							</li>
							<li class="collection-item blue-border white-space-pre-line">
								<strong>Description</strong> : <% q.queue_type.description %>
							</li>
							<li class="collection-item blue-border">
								<strong>Counter</strong> : <% q.counter %>
							</li>
							<li class="collection-item blue-border">
								<strong>Working time</strong> :  <% QueueAdmin.convertTime(q.workingtime) | date:'d MMM y HH:mm น.' %>
							</li>
							<li class="collection-item blue-border">
								<strong>Service time per user</strong> :  <% q.workmin %>
							</li>
							<li class="collection-item blue-border">
								<strong>Start</strong> : <% QueueAdmin.convertTime(q.open) | date:'d MMM y HH:mm น.' %>
							</li>
							<li class="collection-item blue-border">
								<strong>Close</strong> : <% QueueAdmin.convertTime(q.close) | date:'d MMM y HH:mm น.' %>
							</li>
							<li class="collection-item blue-border">
								<strong>Remaining : <timer countdown="QueueAdmin.countd(q.close)"  max-time-unit="'day'" interval="1000">
									<% days %> วัน, <%hours %> ชั่วโมง <% mminutes %> นาที <% sseconds %> วินาที
								</timer>
							</li>
							<li class="collection-item blue-border">
								<strong>Reserved count</strong> : <% q.current %>/<% q.max %>
							</li>
							<li class="collection-item center">
								<button type="button" class="btn waves-effect waves-light blue">
									<i class="fa fa-btn fa-plus-circle"></i> Close
								</button>
							</li>
					  </ul>  		
					</div>
				</div>
			</div>
    </div>
  </div>
  <!-- user list -->
	<div id="userListModal" class="modal">
    <div class="modal-content">
    	<div ng-show="innerloading" class="center-align"><br/><br/><innerloading></innerloading><br/><br/></div>
    	<div ng-show="!innerloading">
	      <table class="table table-striped table-hover responsive-table">
	      	<thead>
	      		<tr>
	      			<th>#</th>
	      			<th>Name</th>
	      			<th>Username</th>
	      			<th>Time</th>
	      		</tr>
	      	</thead>
	      	<tbody>
	      		<tr ng-repeat="user in userinQueue">
	      			<td><% $index+1 %></td>
	      			<td><% user.user.name %></td>
	      			<td><% user.user.username %></td>
	      			<td><% QueueAdmin.convertTime(user.time) | date:'d MMM y HH:mm น.' %></td>
	      		</tr>
	      	</tbody>
	      </table>
			</div>
    </div>
    <div class="modal-footer">
        <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
	</div>
	<!-- reserved modal -->
	<div id="reservedModal" class="modal">
    <div class="modal-content">
    	<div ng-show="innerloading" class="center-align"><br/><br/><innerloading></innerloading><br/><br/></div>
    	<div ng-show="!UserReserved.length && !innerloading">
    		<p class="flow-text center-align">NO RESERVED QUEUE.</p>
    	</div>
    	<div ng-show="UserReserved.length && !innerloading">
	      <div class="col s12 m12 l12" ng-repeat="reserved in UserReserved">
					<div class="card-panel grey darken-3">
						<div class="card-content white-text">
							<p class="flow-text">
								<p>Name : <% reserved.mainqueue[0].name %> </p> <br/>
								<p>Working time : <% QueueAdmin.convertTime(reserved.mainqueue[0].workingtime) | date:'d MMM y HH:mm น.' %></p><br/>
								<p>Avg. time : <% reserved.mainqueue[0].workmin %></p><br/>
								<p>Queue time : <% QueueAdmin.convertTime(reserved.time) | date:'d MMM y HH:mm น.' %></p><br/>
								<p>Remaining : <timer countdown="QueueAdmin.countd(reserved.time)"  max-time-unit="'day'" interval="1000">
									<% days %> วัน, <%hours %> ชั่วโมง <% mminutes %> นาที <% sseconds %> วินาที
								</timer></p><br/>
								<p>Status : <% reserved.isAccept | uppercase %> </p><br/>
	    					<p>
	    						Verify code : <a tooltipped class="btn blue lighten-2" data-position="right" data-delay="50" data-tooltip="<% reserved.captcha_key %>">SHOW</a>
								</p><br/>
								<button type="button" class="btn red" ng-click="QueueAdmin.deleteUserQueue('Reserved',reserved.id)" onclick="return confirm('Confirm delete ?')">DELETE</button>
							</p>
						</div>
					</div>
				</div>
			</div>
    </div>
    <div class="modal-footer">
        <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
	</div>
	<!-- history modal -->
	<div id="historyModal" class="modal">
    <div class="modal-content">
      <div ng-show="innerloading" class="center-align"><br/><br/><innerloading></innerloading><br/><br/></div>
      <div ng-show="!UserHistory.length && !innerloading">
      	<p class="flow-text center-align">NO RESERVED HISTORY.</p>
    	</div>
      <div ng-show="UserHistory.length && !innerloading">
      	<div class="col s12 m12 l12" ng-repeat="history in UserHistory">
	      	<div class="card-panel lighten-2" ng-class="(history.isAccept == 'yes')?'light-green':'red'">
						<div class="card-content white-text">
							<p class="flow-text">
								<p>Name : <% history.mainqueue[0].name %> </p> <br/>
								<p>Working time : <% QueueAdmin.convertTime(history.mainqueue[0].workingtime) | date:'d MMM y HH:mm น.' %></p><br/>
								<p>Avg. time : <% history.mainqueue[0].workmin %></p><br/>
								<p>Queue time : <% QueueAdmin.convertTime(history.time) | date:'d MMM y HH:mm น.' %></p><br/>
								<p>Remaining : <timer countdown="QueueAdmin.countd(history.mainqueue[0].close)"  max-time-unit="'day'" interval="1000">
									<% days %> วัน, <%hours %> ชั่วโมง <% mminutes %> นาที <% sseconds %> วินาที
								</timer></p><br/>
								<p>Status : <% history.isAccept | uppercase %> </p><br/>
	    					<p>
	    						Verify code : <a tooltipped class="btn blue lighten-2" data-position="right" data-delay="50" data-tooltip="<% history.captcha_key %>">SHOW</a>
								</p><br/>
								<button type="button" class="btn red" ng-click="QueueAdmin.deleteUserQueue('History',history.id)" onclick="return confirm('Confirm delete ?')">DELETE</button>
								<br/>
							</p>
						</div>
					</div>
				</div>
      </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
    </div>
	</div>
</div>

@endsection
@section('js')
	@if(Session::has('success'))
		<script>
			Materialize.toast('{{ Session::get('success') }}',3000,'rounded');
		</script>
	@endif

		<script>
	(function()
	{

		// Main Controller
		angular
			.module('AdminApp', ['ui.materialize', 'QueueAdmin', 'UserAdmin', 'ngAnimate', 'angularUtils.directives.dirPagination', 'ngLocale', 'timer'],setInterpolate)
			.controller('QueueAdminCtrl', ['$scope', 'QueueAdminService', 'userAdminService','$http',QueueAdminCtrl])
			.constant("CSRF_TOKEN", '{{ csrf_token() }}')
			.directive('loading', LoadingDirective)
			.directive('innerloading', innerLoadingDirective)
			.config(function ($httpProvider) {
			  $httpProvider.useApplyAsync(true);
			});

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

			 function innerLoadingDirective() {
	      return {
	        restrict: 'E',
	        replace:true,
	        template: '<div class="preloader-wrapper big active"><div class="spinner-layer spinner-blue"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div>',
	        link: function (scope, element, attr) {
	              scope.$watch('innerloading', function (val) {
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

			function QueueAdminCtrl($scope,QueueAdminService,userAdminService,$http){
				
				$scope.loading = true;
				$scope.innerloading = true;
				$scope.currentPage = 1;
				$scope.pageSize = 10;
				$scope.sort = 'end';
				$scope.reverse = false;

				// Variable
				// 
				$scope.tab = 0;
				$scope.loading = false;
				$scope.Queues;
				$scope.Users;
				$scope.User;
				$scope.UserReserved;
				$scope.queuetype;
				$scope.openModal = true;

				// Service
				// 
				this.selectedTab = function(tab)
				{
					$scope.tab = tab;
					switch (tab)
					{
						case 1: this.getUsers(); break;
						case 2: this.getQueues(); break;
						case 3: this.getCheckUser(); break;
					}
				}

				this.getQueues = function(){
					$scope.loading = true;
					QueueAdminService.getQueues()
						.then(function(data){
							$scope.Queues = data.result;
							$scope.loading = false;
							console.log($scope.Queues);
						})
				}

				this.getQueue = function(id){
					$scope.innerloading = true;
					QueueAdminService.getQueue(id)
						.then(function(data){
							$scope.singleQueue = data.result;
							$scope.innerloading = false;
						})
				}

				this.getUserInQueue = function(id){
					$scope.innerloading = true;
					QueueAdminService.getUserInQueue(id)
						.then(function(data){
							$scope.userinQueue = data.result;
							$scope.innerloading = false;
						})
				}

				this.getUsers = function(){
					$scope.loading = true;
					userAdminService.getUsers()
						.then(function(data){
							$scope.Users = data.result;
							$scope.loading = false;
						})
				}

				this.getUser = function(id)
				{
					$scope.innerloading = true;
					userAdminService.getUser(id)
						.then(function(data){
							$scope.User = data.result;
							$scope.innerloading = false;
						})
				}

				this.getUserReserved = function(id)
				{
					$scope.innerloading = true;
					userAdminService.getUserReserved(id)
						.then(function(data){
							console.log(data);
							$scope.UserReserved = data.result;
							$scope.innerloading = false;
						})
				}

				this.getUserHistory = function(id)
				{
					$scope.innerloading = true;
					userAdminService.getUserHistory(id)
						.then(function(data){
							$scope.UserHistory = data.result;
							$scope.innerloading = false;
						})
				}

				$scope.newQueue = {
						'name' : '',
						'queuetype_id' : 1,
						'counter' : '',
						'workingtime' : '',
						'workingtime_time' : '',
						'workmin' : '',
						'open' : '',
						'open_time' : '',
						'close' : '',
						'close_time' : '',
						'max' : ''
					}

				this.newQueueinit = function(){
					QueueAdminService.getQueueType()
						.then(function(data){
							$scope.queuetype = data.result;
						});
				} 
				

				this.NewQueue = function(newQueue){
					QueueAdminService.addNewQueue(newQueue)
						.then(function(data){
							$scope.addQueueStatus = data.status;
							$scope.newQueueResult = data.result;
							if(data.status == 'Success'){
								$scope.newQueue = {
									'name' : '',
									'queuetype_id' : 1,
									'counter' : '',
									'workingtime' : '',
									'workingtime_time' : '',
									'workmin' : '',
									'open' : '',
									'open_time' : '',
									'close' : '',
									'close_time' : '',
									'max' : ''
								}
								$scope.loading = true;
								QueueAdminService.getQueues()
									.then(function(data){
										$scope.Queues = data.result;
										$scope.loading = false;
									})
							}
						});
				}

				this.deleteUser = function(user){
					userAdminService.deleteUser(user)
						.then(function(data){
							alert(data.result);
							userAdminService.getUsers()
							.then(function(data){
								$scope.Users = data.result;
								$scope.loading = false;
							})
						})
				}

				this.deleteUserQueue = function(action,queue){
					userAdminService.deleteUserQueue(queue)
						.then(function(data){
							if(action == 'Reserved'){
								userAdminService.getUserReserved()
								.then(function(data){
									$scope.UserReserved = data.result;
								})
							}
							else if(action == 'History'){
								userAdminService.getUserHistory()
								.then(function(data){
									$scope.UserHistory = data.result;
								})
							}
							$scope.openModal = false;

						})
				}

				this.completeModal = function(){
					$scope.innerloading = true;
				}

				this.isSelected = function(tab)
				{
					return $scope.tab === tab;
				}
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

			}

			angular
			.module('QueueAdmin', [])
			.factory('QueueAdminService', ['$http','$q', QueueAdminService]);

			function QueueAdminService($http,$q)
			{

				var getQueues = function()
				{
					var request = $http.get("App/getQueues");
					return( request.then( handleSuccess, handleError ) );
				}

				var getActiveQueues = function()
				{

					var request = $http.get("Admin/getActiveQueues");
					return( request.then( handleSuccess, handleError ) );
				}

				var getRunningQueues = function()
				{

					var request = $http.get("Admin/getRunningQueues");
					return( request.then( handleSuccess, handleError ) );
				}

				var getQueue = function(id)
				{

					var request = $http.get("App/getQueue/"+id);
					return( request.then( handleSuccess, handleError ) );
				}

				var getUserInQueue = function(id)
				{
					var request = $http.get("Admin/getUserInQueue/"+id);
					return( request.then( handleSuccess, handleError ) );
				}

				var getQueueType = function()
				{

					var request = $http.get("App/getQueueType");
					return( request.then( handleSuccess, handleError ) );
				}

				var addNewQueue = function(newQueue)
				{

					var request = $http.post("Admin/addNewQueue",newQueue);
					return( request.then( handleSuccess, handleError ) );
				}

				return {
					getActiveQueues : getActiveQueues,
					getRunningQueues : getRunningQueues,
					getQueue : getQueue,
					getQueues : getQueues,
					getQueueType : getQueueType,
					addNewQueue : addNewQueue,
					getUserInQueue : getUserInQueue,
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

			angular
			.module('UserAdmin', [])
			.factory('userAdminService', ['$http','$q', userAdminService]);

			function userAdminService($http,$q)
			{

				var getUsers = function()
				{

					var request = $http.get("Admin/getUsers");
					return( request.then( handleSuccess, handleError ) );
				}

				var getUser = function(id)
				{

					var request = $http.get("Admin/getUser/"+ id );
					return( request.then( handleSuccess, handleError ) );
				}

				var getUserReserved = function(id)
				{

					var request = $http.get("Admin/getUserReserved/"+id);
					return( request.then( handleSuccess, handleError ) );
				}

				var getUserHistory = function(id)
				{

					var request = $http.get("Admin/getUserHistory/"+id);
					return( request.then( handleSuccess, handleError ) );
				}

				var deleteUser = function(id)
				{

					var request = $http.get("Admin/deleteUser/"+id);
					return( request.then( handleSuccess, handleError ) );
				}

				var deleteUserQueue = function(id)
				{

					var request = $http.get("Admin/deleteUserQueue/"+id);
					return( request.then( handleSuccess, handleError ) );
				}

				return {
					getUserHistory : getUserHistory,
					getUserReserved : getUserReserved,
					getUser : getUser,
					getUsers : getUsers,
					deleteUser : deleteUser,
					deleteUserQueue : deleteUserQueue,
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