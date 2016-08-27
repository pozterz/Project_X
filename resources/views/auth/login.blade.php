@extends('main.template') @section('content')
<div class="container">
	<div class="row">
		<div class="col l3"> </div>
		<div class="col s12 m12 l6">
			<div class="content card center z-depth-2">
				<div class="card-content">
					<form  role="form" method="POST" action="{{ url('/login') }}">
					<span class="card-title">Login</span>
					  <div class="row">
					  	{!! csrf_field() !!}
					  </div>
					  <div class="row">
						<div class="input-field col s12">
							<input id="username" type="text" name="username" class="validate{{ $errors->has('username') ? ' invalid' : '' }}">
							@if ($errors->has('username'))
								<label for="username" data-error="{{ $errors->first('username') }}">Username</label>
							@else
								<label for="username" data-success="Validated">Username</label>
							@endif
						</div>
					  </div>
					  <div class="row">
						<div class="input-field col s12">
							<input id="password" type="password" name="password" class="validate{{ $errors->has('password') ? ' invalid' : '' }}">
							@if ($errors->has('password'))
								<label for="password" data-error="{{ $errors->first('password') }}">Password</label>
							@else
								<label for="password" data-success="Validated">Password</label>
							@endif
						</div>
					  </div>
					  <div class="row">
					  	<div class="center">
						  	<input type="checkbox" name="remember" id="remember">
						  	<label for="remember"> Remember Me</label>
					  	</div>
					  </div>
					  <div class="row">
					  	<div class="center">
					  		<button type="submit" class="btn waves-effect waves-blue blue">
								<i class="fa fa-btn fa-sign-in"></i> Login
							</button> 
							<a class="btn waves-effect waves-blue blue" href="{{ url('/password/reset') }}"><i class="fa fa-btn fa-key"></i> Forgot Your Password?</a>
						</div>
					  </div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection