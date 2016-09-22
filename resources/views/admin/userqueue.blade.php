@extends('main.template')

@section('content')

<div class="container">
	<div class="row">
		<div class="col s12 m12 l12">
			<div class="card-panel">
				<div class="card-content">
					@if(count($user))
					<span class="card-title flow-text">Reserved Queue : {{$user->user_info->name}}</span><br/><br/>
					<a href="{{ url('/admin') }}" title="Back">
						<button type="button" class="left btn-floating btn waves-effect waves-light red">
							<i class="fa fa-chevron-circle-left"></i>
						</button>
					</a>
					<a href="{{ url('/admin/newActivity') }}" title="New Activity">
						<button type="button" class="right btn-floating btn waves-effect waves-light green">
							<i class="fa fa-plus"></i>
						</button>
					</a>
					<div class="row">
						<div class="content">
							@if(isset($userqueues))
							<table class="centered highlight bordered">
								<thead>
									<tr>
										<th data-field="id">#</th>
										<th data-field="name">Name</th>
										<th data-field="queue_time">Queue Time</th>
										<th data-field="count">Count</th>
										<th data-field="status">Status</th>
										<th data-field="detail">Detail</th>
										<th data-field="list">List</th>
										<th data-field="delete">Delete</th>
									</tr>
								</thead>
								<tbody>
									@foreach($userqueues as $userqueue)
									<tr>
										<td>{{ $userqueue->id }}</td>
										<td>{{ $userqueue->mainqueue->first()->queue_name }}</td>
										<td>{{ $userqueue->queue_time }}</td>
										<td>{{ $userqueue->mainqueue->first()->current_count }}/{{ $userqueue->mainqueue->first()->max_count }}</td>
										<td id="status">
											@if($userqueue->mainqueue->first()->current_count == $userqueue->mainqueue->first()->max_count)
												<span class="red-text">Full</span>
											@elseif($userqueue->mainqueue->first()->start > Carbon\Carbon::now())
												<span class="blue-text">Ready</span>
											@elseif($userqueue->mainqueue->first()->end >= Carbon\Carbon::now() && $userqueue->mainqueue->first()->start <= Carbon\Carbon::now())
												<span class="green-text">Begin</span>
											@elseif($userqueue->mainqueue->first()->end < Carbon\Carbon::now())
												<span class="red-text">Closed</span>
											@endif
										</td>
										<td>
											<a href="{{ url('admin/activities') }}/{{$userqueue->mainqueue->first()->id}}" class="btn-floating waves-effect waves-light btn"><i class="fa fa-info"></i></a>
										</td>
										<td>
											<a class="btn-floating waves-effect waves-light orange btn"><i class="fa fa-check-circle"></i></a>
										</td>
										<td>
											<a href="{{ url('admin/deleteActivities') }}/{{$userqueue->mainqueue->first()->id}}" class="btn-floating waves-effect waves-light red btn" onclick="return confirm('Confirm delete ?')">
												<i class="fa fa-close"></i>
											</a>
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
							<br/>
							<div align="center">
								{!! $userqueues->render() !!}
							</div>
							@endif
						</div>
					</div>
					@else
						<span class="card-title flow-text">No Reserved Queue</span>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

@endsection