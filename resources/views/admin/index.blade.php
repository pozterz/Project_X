@extends('main.template')

@section('content')
<div class="content">
	<div class="container">
		<div class="row card-panel">
			<div class="col s12 m12 l12">
				<div class="col s2 m4 l4">
					<a href="{{ url('admin/users') }}">
						<div class="card-panel z-depth-2 pink lighten-1">
							<div class="card-content white-text">
								<p class="flow-text"><i class="fa fa-users"></i>  Manage Users.</p>
							</div>
						</div>
					</a>
				</div>
				<div class="col s2 m4 l4">
					<a href="{{ url('admin/activities') }}">
						<div class="card-panel z-depth-2 indigo lighten-1">
							<div class="card-content white-text">
								<p class="flow-text"><i class="fa fa-calendar"></i>  Manage Activities.</p>
							</div>
						</div>
					</a>
				</div>
				<div class="col s2 m4 l4">
					<a href="{{ url('admin/userqueues') }}">
						<div class="card-panel z-depth-2 purple lighten-1">
							<div class="card-content white-text">
								<p class="flow-text"><i class="fa fa-sort"></i>  User's Queue.</p>
							</div>
						</div>
					</a>
				</div>
				<div class="col s2 m4 l4">
					<a href="{{ url('admin/queuetable') }}">
						<div class="card-panel z-depth-2 orange lighten-1">
							<div class="card-content white-text">
								<p class="flow-text"><i class="fa fa-table"></i>  View Queue Table.</p>
							</div>
						</div>
					</a>
				</div>
				<div class="col s2 m4 l4">
					<a href="{{ url('admin/userlog') }}">
						<div class="card-panel z-depth-2 blue lighten-1">
							<div class="card-content white-text">
								<p class="flow-text"><i class="fa fa-bar-chart-o"></i>  View User History.</p>
							</div>
						</div>
					</a>
				</div>
				<div class="col s2 m4 l4">
					<a href="{{ url('admin/log') }}">
						<div class="card-panel z-depth-2 red lighten-1">
							<div class="card-content white-text">
								<p class="flow-text"><i class="fa fa-bar-chart"></i>  View Queue History.</p>
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection