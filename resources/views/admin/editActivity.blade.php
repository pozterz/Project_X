@extends('main.template')

@section('content')
<div class="container">
	<div class="row">
		<div class="col s12 m2 l2"> </div>
			<div class="col s12 m8 l8">
				<div class="content card z-depth-2">
					<div class="card-content">
						<span class="card-title flow-text">Edit Activity</span><br/><br/>
						<div class="row">
							<form class="col s12" method="POST" action="{{ url('/admin/editActivity') }}">
								{!! csrf_field() !!}
								<div class="row">
									<div class="input-field col s12">
										<input id="queue_name" type="text" name="queue_name" class="validate{{ $errors->has('queue_name') ? ' invalid' : '' }}" value="{{ $Activity->queue_name }}" length="150">
										@if ($errors->has('queue_name'))
											<label for="queue_name" data-error="{{ $errors->first('queue_name') }}">Activity name</label>
										@else
											<label for="queue_name" data-error="Please input 6 charactor or more" data-success="Validated">Activity Name</label>
										@endif
									</div>
								</div>
								<div class="row">
									<div class="input-field col s12">
										<input id="counter" type="text" name="counter" class="validate{{ $errors->has('counter') ? ' invalid' : '' }}" length="100" value="{{ $Activity->counter }}">
										@if ($errors->has('counter'))
											<label for="counter" data-error="{{ $errors->first('counter') }}">Counter</label>
										@else
											<label for="counter" data-error="Please input 6 charactor or more" data-success="Validated">Counter</label>
										@endif
									</div>
								</div>
								<div class="row">
									<div class="input-field col s6">
										<input id="service_time" type="number" name="service_time" class="validate{{ $errors->has('service_time') ? ' invalid' : '' }}" value="{{ $Activity->service_time }}">
										@if ($errors->has('service_time'))
											<label for="service_time" data-error="{{ $errors->first('service_time') }}">Service Time (minute)</label>
										@else
											<label for="service_time" data-error="Please input 6 charactor or more" data-success="Validated">Service Time (minute)</label>
										@endif
									</div>
									<div class="input-field col s6">
										<input id="max_count" type="number" name="max_count" class="validate{{ $errors->has('max_count') ? ' invalid' : '' }}" value="{{ $Activity->max_count }}">
										@if ($errors->has('max_count'))
											<label for="max_count" data-error="{{ $errors->first('max_count') }}">Limit</label>
										@else
											<label for="max_count" data-error="Please input 6 charactor or more" data-success="Validated">Limit</label>
										@endif
									</div>
								</div>
								<div class="row">
									<div class="input-field col s6">
										<input id="opentime" type="date" name="opentime" class="datepicker" >
										@if ($errors->has('opentime'))
											<label for="opentime" data-error="{{ $errors->first('opentime') }}">Open Date</label>
										@else
											<label for="opentime">Open Date</label>
										@endif
									</div>
									<div class="input-field col s6">
										<input id="open_timepicker" type="text" name="opentime_time" class="timepicker">
										@if ($errors->has('opentime_time'))
											<label for="open_timepicker" data-error="{{ $errors->first('opentime_time') }}">Open Time</label>
										@else
											<label for="open_timepicker">Open Time</label>
										@endif
									</div>
									
								</div>
								<div class="row">
									<div class="input-field col s6">
										<input id="start" type="date" name="start" class="datepicker">
										@if ($errors->has('start'))
											<label for="start" data-error="{{ $errors->first('start') }}">Start Date</label>
										@else
											<label for="start">Start Date</label>
										@endif
									</div>
									<div class="input-field col s6">
										<input id="start_timepicker" type="text" name="start_time" class="timepicker">
										@if ($errors->has('start_time'))
											<label for="start_timepicker" data-error="{{ $errors->first('start_time') }}">Start Time</label>
										@else
											<label for="start_timepicker">Start Time</label>
										@endif
									</div>
									
								</div>
								<div class="row">
									<div class="input-field col s6">
										<input id="end" type="date" name="end" class="datepicker">
										@if ($errors->has('end'))
											<label for="end" data-error="{{ $errors->first('end') }}">End Date</label>
										@else
											<label for="end">End Date</label>
										@endif
									</div>
									<div class="input-field col s6">
										<input id="end_timepicker" type="text" name="end_time" class="timepicker">
										@if ($errors->has('end_time'))
											<label for="end_timepicker" data-error="{{ $errors->first('end_time') }}">End Time</label>
										@else
											<label for="end_timepicker">End Time</label>
										@endif
									</div>
								</div>
								<input type="hidden" name="id" value="{{ $Activity->id }}">
								<div class="row center">
									<button type="submit" class="btn waves-effect waves-light green"><i class="fa fa-check-circle"></i> Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
	</div>
</div>

@endsection

@section('js')
<script>
	$('#open_timepicker').pickatime({
	    default: 'now',
	    twelvehour: false, 
	    donetext: 'OK',
	 	autoclose: false
	});
	$('#start_timepicker').pickatime({
	    default: 'now',
	    twelvehour: false, 
	    donetext: 'OK',
	 	autoclose: false
	});
	$('#end_timepicker').pickatime({
	    default: 'now',
	    twelvehour: false, 
	    donetext: 'OK',
	 	autoclose: false
	});
	
</script>

@endsection