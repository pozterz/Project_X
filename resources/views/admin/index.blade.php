@extends('main.template')

@section('content')
<div class="content">
	<div class="container">
		<div class="row card-panel">
			<div class="col s12 m12 l12">
				<div class="col s12 m4 l4">
					<a href="{{ url('admin/users') }}">
						<div class="grow card-panel z-depth-2 red lighten-1">
							<div class="card-content white-text">
								<p class="flow-text"><i class="fa fa-users"></i>  Manage Users.</p>
							</div>
						</div>
					</a>
				</div>
				<div class="col s12 m4 l4">
					<a href="{{ url('admin/activities') }}">
						<div class="grow card-panel z-depth-2 orange lighten-1">
							<div class="card-content white-text">
								<p class="flow-text"><i class="fa fa-calendar"></i>  Manage Activities.</p>
							</div>
						</div>
					</a>
				</div>
				<div class="col s12 m4 l4">
					<a href="{{ url('admin/log') }}">
						<div class="grow card-panel z-depth-2 blue lighten-1">
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
@section('js')
	@if(Session::has('success'))
		<script>
			Materialize.toast('{{ Session::get('success') }}',3000,'rounded');
		</script>
	@endif
@endsection