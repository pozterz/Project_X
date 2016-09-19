@extends('main.template')

@section('content')
<div class="container">
	<div class="row">
		<div class="col s12 m12 l12">
			<div class="card-panel">
				<div class="card-content">
					<span class="card-title flow-text">Manage Users</span><br/><br/>
					<a href="{{ url('/admin') }}" title="Back">
						<button type="button" class="left btn-floating btn waves-effect waves-light red"><i class="fa fa-chevron-circle-left"></i>
						</button>
					</a>
					<a href="{{ url('/admin/AddUser') }}" title="Add new user">
						<button type="button" class="right btn-floating btn waves-effect waves-light green">
							<i class="fa fa-plus"></i>
						</button>
					</a>
					<div class="row">
						<div class="content">
							<table class="centered bordered highlight responsive-table">
								<thead>
									<tr>
										<th data-field="id">#</th>
										<th data-field="name">Name</th>
										<th data-field="username">Level</th>
										<th data-field="gender">Gender</th>
										<th data-field="profile">Profile</th>
										<th data-field="userqueue">Queue</th>
										<th data-field="history">History</th>
										<th data-field="delete">Delete</th>
									</tr>
								</thead>
								<tbody>
									@foreach($users as $user)
									<tr>
										<td>{{$user->id}}</td>
										<td>{{$user->user_info->name}}</td>
										<td>{{$user->level}}</td>
										<td>{{$user->user_info->gender}}</td>
										<td>
											<a href="{{ url('admin/user') }}/{{$user->id}}" class="btn-floating waves-effect waves-light btn"><i class="fa fa-user"></i></a>
										</td>
										<td>
											<a class="btn-floating waves-effect waves-light orange btn"><i class="fa fa-calendar-check-o"></i></a>
										</td>
										<td>
											<a  class="btn-floating waves-effect waves-light blue btn"><i class="fa fa-history"></i></a>
										</td>
										<td>
											<a href="{{ url('admin/delete') }}/{{$user->id}}" class="btn-floating waves-effect waves-light red btn"><i class="fa fa-close"></i></a>
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
							<br/>
							<div align="center">
								{!! $users->links() !!}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
