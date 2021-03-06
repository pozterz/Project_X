@extends('main.template')

@section('content')
<style type="text/css" media="screen">
.white-space-pre-line {
    white-space: pre-line;
}
</style>
@if(Request::is('User/Reserve/'.$id))

<div  ng-app="ReserveApp" ng-controller="ReserveCtrl as Reserve">
	<div class="row">
		<div class="col s12 m6 l6">
			<div class="card">
				<div ng-show="loading" class="center-align"><br/><br/><loading></loading><br/><br/><br/><br/></div>
				<div ng-show="!QueueData.length && !loading">
					<br/><br/><br/>
    			<p class="flow-text center-align">QUEUE NOT FOUND.</p>
    			<br/><br/><br/>
	    	</div>
				<div class="card-panel white z-depth-1" ng-repeat="Queue in QueueData" ng-show="QueueData.length && !loading">
					 <ul class="collection with-header">
						<li class="collection-header red-border">				
							<h5 class="flow-text"><i class="fa fa-hashtag"></i> <strong>Queue name</strong> : <% Queue.name %>
							<br>
						</li>
						<form action="{{ url('/User/Reserve')}}" method="POST">
							{{ csrf_field() }}
							<li class="collection-item blue-border">
								<strong>Type</strong> : <% Queue.queue_type.name %>
							</li>
							<li class="collection-item blue-border white-space-pre-line" ng-if="Queue.queue_type.requirement.length">
								<strong>Requirement</strong> : <% Queue.queue_type.requirement %>
							</li>
							<li class="collection-item blue-border white-space-pre-line" ng-if="Queue.queue_type.document.length">
								<strong>Document</strong> : <% Queue.queue_type.document %>
							</li>
							<li class="collection-item blue-border white-space-pre-line" ng-if="Queue.queue_type.description.length">
								<strong>Description</strong> : <% Queue.queue_type.description %>
							</li>
							<li class="collection-item blue-border">
								<strong>Counter</strong> : <% Queue.counter %> | <% Queue.user.name %>
							</li>
							<li class="collection-item blue-border">
								<p class="flow-text">Available Time</p>
							</li>
							<li class="collection-item blue-border">
								<strong>Start</strong> :  <% Reserve.convertTime(Queue.service_start) | date:'d MMM y HH:mm น.' %>
							</li>
							<li class="collection-item blue-border">
								<strong>End</strong> :  <% Reserve.convertTime(Queue.service_end) | date:'d MMM y HH:mm น.' %>
							</li>
							<li class="collection-item blue-border">
								<strong>Max service time/queue</strong> :  <% Queue.max_minutes %> Minutes.
							</li>
							<li class="collection-item blue-border">
								<p class="flow-text">Reserve Detail</p>
							</li>
							<li class="collection-item blue-border">
								<strong>Start</strong> : <% Reserve.convertTime(Queue.open) | date:'d MMM y HH:mm น.' %>
							</li>
							<li class="collection-item blue-border">
								<strong>Close</strong> : <% Reserve.convertTime(Queue.close) | date:'d MMM y HH:mm น.' %>
							</li>
							<li class="collection-item blue-border">
								<strong>Remaining : <timer countdown="Reserve.countd(Queue.close)"  max-time-unit="'day'" interval="1000">
															<% days %> วัน, <%hours %> ชั่วโมง <% mminutes %> นาที <% sseconds %> วินาที
														</timer>
							</li>
							<li class="collection-item blue-border">
								<strong>Reserved count</strong> : <% Queue.current %>/<% Queue.max %>
							</li>
							<li class="collection-item blue-border" ng-if="QueueData.status == 'Opening'">
								 <button ngf-select="uploadFiles($files, $invalidFiles)" multiple
          accept="image/*,application/pdf" ngf-max-size="3MB" class="btn waves-effect waves-light pink">
      							<i class="material-icons">description</i> Upload files
      						</button>
      						<p class="red-text"><br/>*สามารถอัพโหลดได้ทีละหลายไฟล์ในครั้งเดียว โดยการกด Shift ค้าง แล้วคลิกที่ไฟล์ที่ต้องการอัพโหลด เมื่อเลือกแล้วไฟล์จะถูกอัพโหลดทันที</p>
      						<li ng-repeat="uploaded in uploadedFiles" class="collection-item blue-border">
      							<% uploaded %> Uploaded.
      						</li>
      						<li ng-repeat="f in files" class="collection-item blue-border"><% f.name %> <% f.$errorParam %>
							      <span class="progress" ng-show="f.progress >= 0">
							        <div class="determinate" style="width: <%f.progress%>%" ng-bind="f.progress + '%'">
							        	<%f.progress%>
							        </div>
							      </span>
							      <span ng-show="f.result">Upload Successful</span>
							    </li>
							    <li ng-repeat="f in errFiles"  class="collection-item blue-border"><%f.name%> <%f.$error%> <%f.$errorParam%>
    							</li> 
							</li>
							<li class="collection-item blue-border" ng-if="QueueData.status == 'Opening'">
								<p class="flow-text">Reserve</p>
							</li>
							<li class="collection-item blue-border" ng-if="QueueData.status == 'Opening'">
								<div class="row">
									<div class="col s12 m6">
										<input id="reserve_start" type="text" input-date ng-model="Queue.service_start" name="reserve_start" class="validate{{ $errors->has('reserve_start') ? ' invalid' : '' }}" value="{{ old('reserve_start')}}" placeholder="Please choose reserve time.">
										@if ($errors->has('reserve_start'))
											<label for="reserve_start" data-error="{{ $errors->first('reserve_start') }}">reserve_start</label>
										@else
											<label for="reserve_start" data-error="Please choose reserve time." data-success="Validated">Reserve Time</label>
										@endif
									</div>
									<div class="col s12 m6">
										<input id="open_timepicker" type="time" name="reserve_start_time" ng-model="newQueue.service_start_time" input-clock data-twelvehour="false">
									</div>
								</div>
							</li>
							<li class="collection-item blue-border" ng-if="QueueData.status == 'Opening'">
								<input id="reserve_minutes" type="number" name="reserve_minutes" class="validate{{ $errors->has('reserve_minutes') ? ' invalid' : '' }}" value="{{ old('reserve_minutes') }}" pattern=".{[0-9]}">
									@if ($errors->has('reserve_minutes'))
										<label for="reserve_minutes" data-error="{{ $errors->first('reserve_minutes') }}">reserve_minutes</label>
									@else
										<label for="reserve_minutes" data-error="Plase input number." data-success="Validated">Reserve Minutes.</label>
									@endif
							</li>
							<li class="collection-item blue-border" ng-if="QueueData.status == 'Opening'">
								{!! app('captcha')->display()!!}
							</li>
							<li class="collection-item center">
								<input type="hidden" name="id" value="{{ $id }}">
								<input type="hidden" name="ip" value="{{Request::getClientIp()}}">
								<button type="submit" class="btn waves-effect waves-light blue" ng-if="QueueData.status == 'Opening'">
									<i class="fa fa-btn fa-plus-circle"></i> Reserve
								</button>
								<button type="button" class="btn waves-effect waves-light blue disabled" ng-if="QueueData.status == 'Closed'" disabled>
									<i class="fa fa-btn fa-plus-circle"></i> Closed
								</button>
								<button type="button" class="btn waves-effect waves-light blue disabled" ng-if="Queue.current >= Queue.max" disabled>
									<i class="fa fa-btn fa-plus-circle"></i> Full
								</button>
								<button type="button" class="btn waves-effect waves-light blue disabled" ng-if="QueueData.status == 'Waiting'" disabled>
									<i class="fa fa-btn fa-plus-circle"></i> Waiting to Open
								</button>
							</li>
						</form>
					  </ul>  		
				</div>
			</div>
		</div>
		<div class="col s12 m6 l6">
			<div class="card">
				<div ng-show="loading" class="center-align"><br/><br/><loading></loading><br/><br/><br/><br/></div>
				<div ng-show="!QueueData[0].userqueue && !loading">
					<br/><br/><br/>
    			<p class="flow-text center-align">NO RESERVED USER.</p>
    			<br/><br/><br/>
	    	</div>
				<div class="card-panel white z-depth-1" ng-show="QueueData[0].userqueue && !loading">
					<ul class="collection with-header">
						<li class="collection-item red-border">
							 <p class="flow-text">User Reserved Detail <span class="new badge red" data-badge-caption="คน"><% QueueData[0].userqueue.length %></span></p>
						</li>
						<li class="collection-item" ng-class="(user.user_id == {{Auth::user()->id}})?'green-border':'blue-border'" ng-repeat="user in QueueData[0].userqueue">
							<a ng-show="user.user_id == {{Auth::user()->id}}" href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
							No. : <% $index+1 %> <br/>
							From : <% Reserve.convertTime(user.time) | date:'d MMM y HH:mm น.' %><br/>
							To : <% Reserve.convertEndTime(user.time,user.reserved_min) | date:'d MMM y HH:mm น.' %><br/>
							Reserved time : <% user.reserved_min %>  Minutes.

							<br/>
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
			.module('ReserveApp', ['ui.materialize', 'Reserve', 'ngLocale', 'timer','ngFileUpload'],setInterpolate)
			.controller('ReserveCtrl', ['$scope', 'reserveService','Upload','$timeout', ReserveCtrl])
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

			function ReserveCtrl($scope,reserveService,Upload,$timeout)
			{
				$scope.loading = false;
				$scope.uploadedFiles = [];
				$scope.reserve_start;
				$scope.reserve_time;


				$scope.uploadFiles = function(files, errFiles) {
	        $scope.files = files;
	        $scope.errFiles = errFiles;
	        angular.forEach(files, function(file) {
	            file.upload = Upload.upload({
	                url: '{{ url("User/Upload")}}/{{$id}}',
	                data: {file: file}
	            });

	            file.upload.then(function (response) {
	        				$scope.uploadedFiles.push(file.name);
	                $timeout(function () {
	                    file.result = response.data;
	                });
	            }, function (response) {
	                if (response.status > 0)
	                    $scope.errorMsg = response.status + ' : ' + response.data;
	            }, function (evt) {
	                file.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
	                //console.log(file.progress);
	            });
	        });
    		}

				this. countd = function(end){
					var date = new Date();
					var end = new Date(end);
					var diff = (end.getTime() / 1000) - (date.getTime() / 1000);
					return (diff<=0)?0:diff;
				}

				var Status = function(open,close)
        {
        	var now = new Date().getTime();
        	var open = new Date(open).getTime();
        	var close = new Date(close).getTime();
        	if(now > open && now > close) return "Closed";
        	else if(now >= open && now <= close) return "Opening";
        	else if(now < open && now < close) return "Waiting";
        }

				this.getQueue = function(id)
				{
					$scope.loading = true;
					reserveService.getQueue(id)
					.then(function(data){
						$scope.QueueData = data.result;
						console.log($scope.QueueData[0].userqueue);
						$scope.QueueData.status = Status($scope.QueueData[0].open,$scope.QueueData[0].close);
						$scope.loading = false;
					})
				}

				this.convertTime = function(time)
				{
					var date = new Date(time);
					return date;
				}

				this.convertEndTime = function(time,min)
				{
					var date = new Date(time);
					return new Date(date.getTime() + min * 1000 * 60);
				}
				
				this.getQueue({{ $id }});
				@if($errors->has('g-recaptcha-response'))
					Materialize.toast('{{ $errors->first('g-recaptcha-response') }}',3000,'rounded');
				@endif
				@if(Session::has('success'))
					Materialize.toast('{{ Session::get('success') }}',3000,'rounded');
				@endif
			}

			angular
			.module('Reserve', [])
			.factory('reserveService', ['$http','$q', reserveService]);
				function reserveService($http,$q)
				{
					var getQueue = function(id)
					{
						var request = $http.get('{{ url("App/getQueue/") }}' +'/'+ id);
						return( request.then( handleSuccess, handleError ) );
					}

					return {
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

		})();
</script>

@endif

@endsection