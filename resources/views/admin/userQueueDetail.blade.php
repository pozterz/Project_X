@extends('main.template')

@section('content')
<style type="text/css" media="screen">
.white-space-pre-line {
    white-space: pre-line;
}
</style>
@if(Request::is('Admin/UserQueueDetail/'.$queue_id.'/'.$userqueue_id))

<div ng-app="ReserveApp" ng-controller="ReserveCtrl as Reserve">
	<div class="row">
		<div class="col s12 m16 l6">
			<div class="card">
				<div ng-show="loading" class="center-align"><br/><br/><loading></loading><br/><br/><br/><br/></div>
				<div ng-show="!QueueData.queue && !loading">
					<br/><br/><br/>
    			<p class="flow-text center-align">QUEUE NOT FOUND.</p>
    			<br/><br/><br/>
	    	</div>
				<div class="card-panel white z-depth-1" ng-show="QueueData.queue && !loading">
					 <ul class="collection with-header">
						<li class="collection-header red-border">				
							<h5 class="flow-text"><i class="fa fa-hashtag"></i> <strong>Queue name</strong> : <% QueueData.queue.name %>
							<br>
						</li>
							<li class="collection-item blue-border">
								<strong>Type</strong> : <% QueueData.queue.queue_type.name %>
							</li>
							<li class="collection-item blue-border white-space-pre-line">
								<strong>Requirement</strong> : <% QueueData.queue.queue_type.requirement %>
							</li>
							<li class="collection-item blue-border white-space-pre-line">
								<strong>Document</strong> : <% QueueData.queue.queue_type.document %>
							</li>
							<li class="collection-item blue-border white-space-pre-line">
								<strong>Description</strong> : <% QueueData.queue.queue_type.description %>
							</li>
							<li class="collection-item blue-border">
								<strong>Counter</strong> : <% QueueData.queue.counter %>
							</li>
					  </ul>  		
				</div>
			</div>
		</div>
		<div class="col s12 m6 l6">
			<div class="card">
				<div ng-show="loading" class="center-align"><br/><br/><loading></loading><br/><br/><br/><br/></div>
				<div ng-show="!QueueData.userqueue && !loading">
					<br/><br/><br/>
    			<p class="flow-text center-align">QUEUE NOT FOUND.</p>
    			<br/><br/><br/>
	    	</div>
				<div class="card-panel white z-depth-1" ng-show="QueueData.userqueue && !loading">
					<ul class="collection with-header">
						<li class="collection-item blue-border">
							 <p class="flow-text">User Reserved Detail</p>
						</li>
						<li class="collection-item blue-border">
							<strong>Name : </strong><% QueueData.userqueue.user.name %>
						</li>
						<li class="collection-item blue-border">
							<strong>Phone : </strong><% QueueData.userqueue.user.phoneNo %>
						</li>
						<li class="collection-item blue-border">
							<strong>Reserved time : </strong><% Reserve.convertTime(QueueData.userqueue.time) | date:'d MMM y HH:mm น.' %>
						</li>
						<li class="collection-item blue-border">
							<strong>Reserved minutes : </strong><% QueueData.userqueue.reserved_min %>
						</li>
						<li class="collection-item blue-border">
							<strong>Verify code : </strong><% QueueData.userqueue.captcha_key %>
						</li>
						<li class="collection-item blue-border">
							<strong>Status : </strong><span ng-class="QueueData.userqueue.isAccept=='no'?'red-text':'green-text'"><% QueueData.userqueue.isAccept | uppercase %></span>
						</li>
						<li class="collection-item blue-border">
							<strong>Document : </strong><br/>
							@foreach($fileArr as $file)
									<a href="{{url('files'.'/'.$file['filename'])}}">{{$file['filename']}}</a><br/>
							@endforeach
						</li>
						<li class="collection-item center">
							<input type="hidden" name="id" value="{{ $userqueue_id }}">
							<input type="hidden" name="ip" value="{{Request::getClientIp()}}">
							<button type="submit" class="btn waves-effect waves-light green" ng-click="Reserve.Accept({{$queue_id}},{{$userqueue_id}})">
								<i class="fa fa-btn fa-check-circle"></i> Accept
							</button>
							<button type="submit" class="btn waves-effect waves-light red" ng-click="Reserve.Cancel({{$queue_id}},{{$userqueue_id}})">
								<i class="fa fa-btn fa-close"></i> Cancel
							</button>
						</li>
					</ul>
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

		angular
			.module('ReserveApp', ['ui.materialize', 'Reserve', 'ngLocale', 'timer'],setInterpolate)
			.controller('ReserveCtrl', ['$scope', 'detailService', ReserveCtrl])
			.constant("CSRF_TOKEN", '{{ csrf_token() }}')
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

			function ReserveCtrl($scope,detailService)
			{
				$scope.loading = false;
				$scope.uploadedFiles = [];
				$scope.reserve_start;
				$scope.reserve_time;

				this. countd = function(end){
					var date = new Date();
					var end = new Date(end);
					var diff = (end.getTime() / 1000) - (date.getTime() / 1000);
					return (diff<=0)?0:diff;
				}

				this.getUserQueueDetail = function(queue_id,userqueue_id)
				{
					$scope.loading = true;
					detailService.getUserQueueDetail(queue_id,userqueue_id)
						.then(function(data){
							$scope.QueueData = data.result;
							$scope.loading = false;
						})
				}

				this.Accept = function(queue_id,userqueue_id){
					$scope.QueueData.userqueue.isAccept = "yes";
					detailService.Accept(queue_id,userqueue_id);
				}

				this.Cancel = function(queue_id,userqueue_id){
					$scope.QueueData.userqueue.isAccept = "no";
					detailService.Cancel(queue_id,userqueue_id);
				}

				this.convertTime = function(time)
				{
					var date = new Date(time);
					return date;
				}
				
				this.getUserQueueDetail({{ $queue_id }},{{ $userqueue_id }});
				@if($errors->has('g-recaptcha-response'))
					Materialize.toast('{{ $errors->first('g-recaptcha-response') }}',3000,'rounded');
				@endif
				@if(Session::has('success'))
					Materialize.toast('{{ Session::get('success') }}',3000,'rounded');
				@endif
			}

			angular
			.module('Reserve', [])
			.factory('detailService', ['$http','$q', detailService]);
				function detailService($http,$q)
				{
					var getUserQueueDetail = function(queue_id,userqueue_id)
					{
						var request = $http.get('{{ url("Admin/getUserQueueDetail/") }}' +'/'+queue_id+'/'+ userqueue_id);
						return( request.then( handleSuccess, handleError ) );
					}

					var Accept = function(queue_id,userqueue_id)
					{
						var request = $http.get('{{ url("Admin/AcceptQueue/") }}' +'/'+queue_id+'/'+ userqueue_id);
						return( request.then( handleSuccess, handleError ) );
					}

					var Cancel = function(queue_id,userqueue_id)
					{
						var request = $http.get('{{ url("Admin/CancelQueue/") }}' +'/'+queue_id+'/'+ userqueue_id);
						return( request.then( handleSuccess, handleError ) );
					}

					return {
						getUserQueueDetail : getUserQueueDetail,
						Cancel : Cancel,
						Accept : Accept,
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

@endif

@endsection