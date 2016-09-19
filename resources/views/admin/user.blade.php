@extends('main.template')

@section('content')
<div class="container">
	<div class="row">
		<div class="col s12 offset-m2 m8 offset-l2 l8 ">
			<div class="card-panel">
				@if(isset($user))
					<div class="card-content">
						<ul class="collection with-header">
							<li class="collection-item pink-border">
								<h4>
									<span class="card-title flow-text">
										<i class="fa fa-user"></i> Profile #{{$user->id}}
									</span>
								</h4>
							</li>
							<li class="collection-item blue-border">
								Name : {{ $user->user_info->name }}
							</li>
							<li class="collection-item yellow-border">
								Gender : {{ $user->user_info->gender }}
							</li>
							<li class="collection-item orange-border">
								Username : {{ $user->username }}
							</li>
							<li class="collection-item green-border">
								Level : {{ $user->level }}
							</li>
							<li class="collection-item purple-border">
								E-mail : {{ $user->email }}
							</li>
							<li class="collection-item red-border">
								Card ID : {{ $user->user_info->card_id }}
							</li>
							<li class="collection-item lime-border">
								Address : {{ $user->user_info->address }}
							</li>
							<li class="collection-item indigo-border">
								Tel. : {{ $user->user_info->tel }}
							</li>
							<li class="collection-item amber-border">
								Birthday : {{ $user->user_info->birthday->format('j F Y') }}
							</li>
						</ul>
						<div class="center">
							<a href="{{ url('admin/users') }}" title="Back"><button type="button" class="btn waves-effect waves-light red"><i class="fa fa-arrow-left"></i>  Back</button></a>
							<a href="{{ url('admin/edit') }}/{{ $user->id }}"><button type="button" class="btn waves-effect waves-light blue"><i class="fa fa-edit"></i> Edit</button></a>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection
