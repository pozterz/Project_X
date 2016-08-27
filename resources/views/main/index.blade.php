@extends('main.template')

@section('content')
<div class="ctn"
	<div class="row">
		<div class="content">
			<div id="clock"></div><br/>
				<div class="col s12 m12 l12">
				@if(count($user_queue))
					<div class="card-panel">
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
										<td>{{ $queue_detail[$key][0]->queue_name }}</td>
										<td>{{ $queue_detail[$key][0]->opentime->format('d M H:i') }}</td>
										<td>{{ $queue_detail[$key][0]->service_time }}</td>
										<td id="{{ $uq->queue_time }}">{{ $uq->queue_time->format("d M H:i") }}</td>
										<td id="within"></td>
										<td><button type="button" class="btn tooltipped waves-effect waves-light red lighten-2" data-position="right" data-delay="50" data-tooltip="{{ $uq->queue_captcha }}" >Show</button></td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				@endif
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
										<td>{{ $mq->opentime->format("d M H:i") }}</td>
										<td>{{ $mq->service_time }}</td>
										<td>{{ $mq->start->format("d M H:i") }}</td>
										<td id="{{ $mq->end }}">{{ $mq->end->format("d M H:i") }}</td>
										<td id="remaining"></td>
										@if($mq->status == 'ready')
											<td><p class="blue-text">Ready</p></td>
										@elseif($mq->status == 'begin')
											<td><p class="green-text">Begin</p></td>
										@endif

										@if(!Auth::guest())
											<td>
												<a href="{{ url('reserve') }}/{{ $mq->id }}">
													<button class="btn waves-effect waves-light blue" type="button"><i class="fa fa-check"></i> จอง</button>
												</a>
											</td>
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