@extends('main.template')

@section('content')
<div class="ctn"
	<div class="row">
		<div class="content">
			<div id="clock"></div><br/>
				<div class="col s12 m12 l12">
				@if(count($user_queue))
					<div class="card-panel z-depth-2">
						<div class="card-content">
							<h4 class="card-title">Reserved Queue</h4>
							<div class="divider"></div>
							<table class="bordered highlight responsive-table centered">
								<thead>
									<tr>
										<th>#</th>
										<th>Queue Name</th>
										<th>Service Time</th>
										<th>Service/Mins</th>
										<th>Queue Time</th>
										<th>Within</th>
										<th>Verify Key</th>
									</tr>
								</thead>
								<tbody id="UserQueue">
									@foreach($user_queue as $key => $uq)
									<tr>
										<td>{{ $key+1 }}</td>
										<td>{{ $uq->mainqueue->first()->queue_name }}</td>
										<td>{{ $uq->mainqueue->first()->opentime->format("j M H:i") }}</td>
										<td>{{ $uq->mainqueue->first()->service_time }}</td>
										<td id="{{ $uq->queue_time }}">{{ $uq->queue_time->format("j M H:i") }}</td>
										<td id="within"></td>
										<td><button type="button" class="btn tooltipped waves-effect waves-light red lighten-2" data-position="right" data-delay="50" data-tooltip="{{ $uq->queue_captcha }}" >Show</button></td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				@endif
				@if(count($mainqueue))
				
					<div class="card-panel z-depth-2">
						<div class="card-content">
							<h4 class="card-title">All Activity</h4>
							<div class="divider"></div>
							<table class="bordered highlight responsive-table centered">
								<thead>
									<tr>
										<th>#</th>
										<th>Queue Name</th>
										<th>Counter</th>
										<th>Service time</th>
										<th>Service/Mins</th>
										<th>Start</th>
										<th>End</th>
										<th>Count</th>
										<th>Ramaining</th>
										<th>Status</th>
										@if(!Auth::guest())
											<th>จอง</th>
										@endif
									</tr>
								</thead>
								<tbody id="AllQueue">
									@foreach($mainqueue as $key => $mq)
									<tr>
										<td>{{ $key+1 }}</td>
										<td>{{ $mq->queue_name }}</td>
										<td>{{ $mq->counter }}</td>
										<td>{{ $mq->opentime->format("j M H:i") }}</td>
										<td>{{ $mq->service_time }}</td>
										<td id="{{ $mq->start }}">{{ $mq->start->format("j M H:i") }}</td>
										<td id="{{ $mq->end }}">{{ $mq->end->format("j M H:i") }}</td>
										<td>{{ $mq->current_count}}/{{$mq->max_count}}</td>
										<td id="remaining"></td>
										<td id="status">
										@if($mq->start > Carbon\Carbon::now())
											<p class="blue-text">Ready</p>
										@elseif($mq->end >= Carbon\Carbon::now() && $mq->start <= Carbon\Carbon::now())
											@if($mq->current_count == $mq->max_count)
												<p class="red-text">Full</p>
											@else
												<p class="green-text">Begin</p>
											@endif
										@elseif($mq->end < Carbon\Carbon::now() && $mq->opentime > Carbon\Carbon::now())
											<p class="red-text">Closed</p>
										@elseif($mq->end < Carbon\Carbon::now() && $mq->opentime < Carbon\Carbon::now())
											<p class="blue-text">Ended</p>
										@endif
										</td>
										@if(!Auth::guest())
											<td>
											@if(Auth::user()->role_id == 1)
												<a href="{{ url('admin/userList') }}/{{ $mq->id }}">
													<button class="btn waves-effect waves-light orange" type="button"><i class="fa fa-check-circle"></i> List</button>
												</a>
											@elseif($mq->end > Carbon\Carbon::now() && !$mq->userqueue->contains('user_id',Auth::user()->id))
												<a href="{{ url('reserve') }}/{{ $mq->id }}">
													<button class="btn waves-effect waves-light blue" type="button"><i class="fa fa-check"></i> จอง</button>
												</a>
											@elseif($mq->userqueue->contains('user_id',Auth::user()->id))
												<a href="#">
													<button class="btn waves-effect waves-light green" type="button"> จองแล้ว</button>
												</a>
											</td>
											@endif
										@endif
									</tr>
									@endforeach
								</tbody>
							</table>
							<br/>
							<div align="center">
								{!! $mainqueue->render() !!}
							</div>
						</div>
					</div>
				</div>
				@else
					<div class="card-panel">
						<div class="card-content">
							<h4 class="card-title">No Available Activity</h4>
							</div>
						</div>
					</div>
				@endif
				@if(count($passedqueue))
				<div class="card-panel z-depth-2">
						<div class="card-content">
							<h4 class="card-title">Passed Activity</h4>
							<div class="divider"></div>
							<table class="bordered highlight responsive-table centered">
								<thead>
									<tr>
										<th>#</th>
										<th>Queue Name</th>
										<th>Counter</th>
										<th>Service time</th>
										<th>Service/Mins</th>
										<th>Start</th>
										<th>End</th>
										<th>Count</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody id="PassedQueue">
									@foreach($passedqueue as $key => $passed)
									<tr>
										<td>{{ $key+1 }}</td>
										<td>{{ $passed->queue_name }}</td>
										<td>{{ $passed->counter }}</td>
										<td>{{ $passed->opentime->format("j M H:i") }}</td>
										<td>{{ $passed->service_time }}</td>
										<td id="{{ $passed->start }}">{{ $passed->start->format("j M H:i") }}</td>
										<td id="{{ $passed->end }}">{{ $passed->end->format("j M H:i") }}</td>
										<td>{{ $passed->current_count}}/{{$passed->max_count}}</td>
										<td id="status">
										@if($passed->end < Carbon\Carbon::now() && $passed->opentime > Carbon\Carbon::now())
											<p class="red-text">Closed</p>
										@elseif($passed->end < Carbon\Carbon::now() && $passed->opentime < Carbon\Carbon::now())
											<p class="blue-text">Ended</p>
										@endif
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
							<br/>
							<div align="center">
								{!! $passedqueue->render() !!}
							</div>
						</div>
					</div>
				@else
					<div class="card-panel">
						<div class="card-content">
							<h4 class="card-title">No Passed Activity</h4>
							</div>
						</div>
					</div>
				@endif
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
@endsection