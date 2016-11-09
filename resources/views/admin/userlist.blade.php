@extends('main.template')

@section('content')

<div class="container">
	<div class="row">
		<div class="col s12 m12 l12">
			<div class="card-panel">
				<div class="card-content">
					@if(count($mainqueue))
					<span class="card-title flow-text">Reserved User</span><br/><br/>
					<a href="{{ url('/admin/activities') }}" title="Back">
						<button type="button" class="left btn-floating btn waves-effect waves-light red">
							<i class="fa fa-chevron-circle-left"></i>
						</button>
					</a>
					<div class="row">
						<div class="content">
							@if(count($mainqueue))
							<table class="centered highlight bordered">
								<thead>
									<tr>
										<th data-field="id">#</th>
										<th data-field="name">Name</th>
										<th data-field="queue_time">Queue Time</th>
										<th data-field="count">captcha</th>
										<th data-field="status">Status</th>
										<th data-field="accept">Accept</th>
										<th data-field="detail">Detail</th>
									</tr>
								</thead>
								<tbody>
									@foreach($mainqueue as $user)
										@if($user->isAccept == 'no')
									<tr>
										<td>{{ $user->id }}</td>
										<td>{{ $user->user->user_info->name }}</td>
										<td>{{ $user->queue_time }}</td>
										<td>{{ $user->queue_captcha }}</td>
										@if($user->isAccept == 'yes')
											<td><span class="green-text">Yes</span></td>
										@else
											<td><span class="red-text">No</span></td>
										@endif
										<td>
											<a href="{{ url('admin/userList') }}/{{$id}}/user/{{$user->id}}" class="btn-floating waves-effect waves-light green btn" onclick="return confirm('Confirm ?')">
												<i class="fa fa-check"></i>
											</a>
										</td>
										<td>
											<a href="{{ url('admin/user') }}/{{$user->user_id}}" class="btn-floating waves-effect waves-light btn"><i class="fa fa-info"></i></a>
										</td>
									</tr>
										@endif
									@endforeach
								</tbody>
							</table>
							<br/>
							<div align="center">
								{!! $mainqueue->render() !!}
							</div>
							@else
								<br/><br/><span class="card-title flow-text">No Reserved Queue</span>
							@endif
						</div>
					</div>
					@else
						<span class="card-title flow-text">No reserved user.</span>
					@endif
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col s12 m12 l12">
			<div class="card-panel">
				<div class="card-content">
					@if(count($mainqueue))
					<span class="card-title flow-text">Accepted User</span><br/><br/>
					<a href="{{ url('/admin/activities') }}" title="Back">
						<button type="button" class="left btn-floating btn waves-effect waves-light red">
							<i class="fa fa-chevron-circle-left"></i>
						</button>
					</a>
					<div class="row">
						<div class="content">
							@if(count($mainqueue))
							<table class="centered highlight bordered">
								<thead>
									<tr>
										<th data-field="id">#</th>
										<th data-field="name">Name</th>
										<th data-field="queue_time">Queue Time</th>
										<th data-field="count">captcha</th>
										<th data-field="status">Status</th>
										<th data-field="accept">Remove</th>
										<th data-field="detail">Detail</th>
									</tr>
								</thead>
								<tbody>
									@foreach($mainqueue as $user)
										@if($user->isAccept == 'yes')
									<tr>
										<td>{{ $user->id }}</td>
										<td>{{ $user->user->user_info->name }}</td>
										<td>{{ $user->queue_time }}</td>
										<td>{{ $user->queue_captcha }}</td>
										@if($user->isAccept == 'yes')
											<td><span class="green-text">Yes</span></td>
										@else
											<td><span class="red-text">No</span></td>
										@endif
										<td>
											<a href="{{ url('admin/removeAccepted') }}/{{$id}}/user/{{$user->id}}" class="btn-floating waves-effect waves-light red btn" onclick="return confirm('Confirm ?')">
												<i class="fa fa-remove"></i>
											</a>
										</td>
										<td>
											<a href="{{ url('admin/user') }}/{{$user->user_id}}" class="btn-floating waves-effect waves-light btn"><i class="fa fa-info"></i></a>
										</td>
									</tr>
										@endif
									@endforeach
								</tbody>
							</table>
							<br/>
							<div align="center">
								{!! $mainqueue->render() !!}
							</div>
							@else
								<br/><br/><span class="card-title flow-text">No Reserved Queue</span>
							@endif
						</div>
					</div>
					@else
						<span class="card-title flow-text">No Accepted user.</span>
					@endif
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