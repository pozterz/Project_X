@extends('main.template')

@section('content')
<div class="container">
	<div class="row">
		<div class="content">
			<div id="clock"></div><br/>
			@if($user_queue)
			<div class="content card z-depth-2">
				<div class="card-content">
					<h1 class="card-title">User Queue</h1>
					<table class="bordered highlight responsive-table">
						<thead>
							<tr>
								<th>#</th>
								<th>Queue Name</th>
								<th>Queue Time</th>
								<th>Verify Key</th>
							</tr>
						</thead>

						<tbody>
							@foreach($user_queue as $key => $uq)
							<tr>
								<td>{{ $key+1 }}</td>
								<td>{{ $queue_detail[$key][0]->queue_name }}</td>
								<td>{!! dateHelper::thformat($uq->queue_time) !!}</td>
								<td><button type="button" class="btn tooltipped" data-position="right" data-delay="50" data-tooltip="{{ $uq->queue_captcha }}" >Show</button></td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			@endif
			<div class="divider"></div>
			<div class="content card z-depth-2">
				<div class="card-content">
					<h1 class="card-title">All Activity</h1>
					<table class="bordered highlight responsive-table">
						<thead>
							<tr>
								<th>#</th>
								<th>Queue Name</th>
								<th>Counter</th>
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
								<td>{{ $mq->start->format("d M H:i") }}</td>
								<td id="{{ $mq->end }}">{{ $mq->end->format("d M H:i") }}</td>
								<td id="remaining"></td>
								@if($mq->status == 'ready')
									<td><p class="blue-text">Ready</p></td>
								@elseif($mq->status == 'begin')
									<td><p class="green-text">Begin</p></td>
								@endif
								
								@if(!Auth::guest())
									<th>จอง</th>
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
@endsection
