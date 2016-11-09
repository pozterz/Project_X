@extends('main.template')

<!-- Main Content -->
@extends('main.template') @section('content')
<div class="container">
	<div class="row">
		<div class="col l3"> </div>
		<div class="col s12 m12 l6">
			<div class="content card z-depth-2">
				<div class="card-content">
					<form  role="form" method="POST" action="{{ url('/password/email') }}">
					<span class="card-title">Reset Password</span>
					  <div class="row">
					  	{!! csrf_field() !!}
					  </div>
					  <div class="row">
							<div class="input-field col s12">
								<input id="email" type="email" name="email" class="validate{{ $errors->has('email') ? ' invalid' : '' }}">
								@if ($errors->has('email'))
									<label for="email" data-error="{{ $errors->first('email') }}">E-mail</label>
								@else
									<label for="email" data-success="Validated">E-mail</label>
								@endif
							</div>
					  </div>
					  <div class="row">
					  	<div class="center">
					  		<button type="submit" class="btn waves-effect waves-blue blue">
								<i class="fa fa-btn fa-envelope"></i> Send Reset Password Link
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
@if(Session::has('status'))
	<script>
		Materialize.toast('{{ Session::get('status') }}',3000,'rounded');
	</script>
@endif
@endsection