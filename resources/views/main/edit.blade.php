@extends('main.template')

@section('content')
<div class="container">
	<div class="row">
		<div class="col s12 m2 l2"> </div>
			<div class="col s12 m8 l8">
				<div class="content card z-depth-2">
					<div class="card-content">
						<form role="form" method="POST" action="{{ url('/editprofile') }}">
							<span class="card-title">Edit Profile #{{$user->id}}</span>
							{!! csrf_field() !!}
							<div class="row">
								<div class="input-field col s12">
									<input id="email" type="email" name="email" class="validate{{ $errors->has('email') ? ' invalid' : '' }}" value="{{ $user->email }}">
									@if ($errors->has('email'))
										<label for="email" data-error="{{ $errors->first('email') }}">E-mail</label>
									@else
										<label for="email" data-error="Please fill E-mail address" data-success="Validated">E-mail</label>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input id="name" type="text" name="name" class="validate{{ $errors->has('name') ? ' invalid' : '' }}" value="{{ $user->name }}">
									@if ($errors->has('name'))
										<label for="name" data-error="{{ $errors->first('name') }}">Name</label>
									@else
										<label for="name" data-success="Validated">Name</label>
									@endif
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
									<input id="tel" type="text" name="tel" class="validate{{ $errors->has('tel') ? ' invalid' : '' }}" value="{{ $user->phone }}" pattern="[0-9].{9}" length="10">
									@if ($errors->has('tel'))
										<label for="tel" data-error="{{ $errors->first('tel') }}">Phone</label>
									@else
										<label for="tel" data-error="Phone number had 10 digits and number only" data-success="Validated">Phone</label>
									@endif
								</div>
							</div>
							@if(Auth::user()->isModerator(Auth::user()))
							<div class="row">
								<div class="input-field col s12">
									<input id="counter_id" type="text" name="counter_id" class="validate{{ $errors->has('counter_id') ? ' invalid' : '' }}" value="{{ $user->counter_id }}" pattern="\d*">
									@if ($errors->has('counter_id'))
										<label for="counter_id" data-error="{{ $errors->first('counter_id') }}">Counter</label>
									@else
										<label for="counter_id" data-error="Counter number is number only" data-success="Validated">Counter</label>
									@endif
								</div>
							</div>
							@endif
							<input type="hidden" name="ip" value="{{Request::getClientIp()}}">
							<div class="row">
						  	<div class="center">
						  		<button type="submit" class="btn waves-effect waves-light blue">
									<i class="fa fa-btn fa-save"></i> Save
								</button> 
							</div>
						  </div>
					</form>
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