@extends('main.template')

@section('content')
<div class="container">
	<div class="row">
		<div class="col s12 offset-m2 m8 offset-l2 l8 ">
			<div class="card-panel">
				@if(Auth::user())
					<div class="card-content">
						<ul class="collection with-header">
							<li class="collection-item pink-border">
								<h4>
									<span class="card-title flow-text">
										<i class="fa fa-user"></i> Profile #{{Auth::user()->id}}
									</span>
								</h4>
							</li>
							<li class="collection-item blue-border">
								Name : {{ Auth::user()->name }}
							</li>
							<li class="collection-item blue-border">
								Username : {{ Auth::user()->username }}
							</li>
							<li class="collection-item blue-border">
								Level : {{ ucfirst(trans(Auth::user()->getUserRole()->name)) }}
							</li>
							<li class="collection-item blue-border">
								E-mail : {{ Auth::user()->email }}
							</li>
							<li class="collection-item blue-border">
								Phone. : {{ Auth::user()->phone }}
							</li>
						</ul>
						<div class="center">
							<a href="{{ url('/index') }}" title="Back"><button type="button" class="btn waves-effect waves-light red"><i class="fa fa-arrow-left"></i>  Back</button></a>
							<a href="{{ url('/editprofile') }}""><button type="button" class="btn waves-effect waves-light blue"><i class="fa fa-edit"></i> Edit</button></a>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection
