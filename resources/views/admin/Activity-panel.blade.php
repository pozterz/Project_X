@extends('main.template')

@section('content')

<div class="container">
	<div class="row">
		<div class="col s12 m12 l12">
			<div class="card-panel">
				<div class="card-content">
					<span class="card-title flow-text">Manage Activities </span><div class="chip amber lighten-3 right">{{ $mainqueues->total() }} Activities</div><br/><br/>
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
							@if(isset($mainqueues))
							<table class="centered highlight bordered">
								<thead>
									<tr>
										<th data-field="id">#</th>
										<th data-field="name">Name</th>
										<th data-field="start-end">Start - End</th>
										<th data-field="status">Status</th>
										<th data-field="count">Count</th>
										<th data-field="detail">Detail</th>
										<th data-field="list">List</th>
										<th data-field="delete">Delete</th>
									</tr>
								</thead>
							
								<tbody>
									@foreach($mainqueues as $mainqueue)
									<tr>
										<td>{{ $mainqueue->id }}</td>
										<td>{{ $mainqueue->queue_name }}</td>
										<td>{{ $mainqueue->start->format('j M H:i') }} - {{ $mainqueue->end->format('j M H:i') }}</td>
										<td id="status">
											@if($mainqueue->current_count == $mainqueue->max_count)
												<span class="red-text">Full</span>
											@elseif($mainqueue->start > Carbon\Carbon::now())
												<span class="blue-text">Ready</span>
											@elseif($mainqueue->end >= Carbon\Carbon::now() && $mainqueue->start <= Carbon\Carbon::now())
												<span class="green-text">Begin</span>
											@elseif($mainqueue->end < Carbon\Carbon::now())
												<span class="red-text">Closed</span>
											@endif
										</td>
										<td>{{ $mainqueue->current_count }}/{{$mainqueue->max_count}}</td>
										<td>
											<a href="{{ url('admin/activities') }}/{{$mainqueue->id}}" class="btn-floating waves-effect waves-light btn"><i class="fa fa-info"></i></a>
										</td>
										<td>
											<a href="{{ url('admin/userList') }}/{{$mainqueue->id}}" class="btn-floating waves-effect waves-light orange btn"><i class="fa fa-check-circle"></i></a>
										</td>
										<td>
											<a href="{{ url('admin/deleteActivities') }}/{{$mainqueue->id}}" class="btn-floating waves-effect waves-light red btn" onclick="return confirm('Confirm delete ?')">
												<i class="fa fa-close"></i>
											</a>
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
							<br/>
							<div align="center">
								{!! $mainqueues->links() !!}
							</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection